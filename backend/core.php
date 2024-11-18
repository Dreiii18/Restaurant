<?php
// session_start();
require_once(dirname(__FILE__)."/../config/config.php");

class Core
{
    /** @var Database */
    protected $db;
    protected $orderType;
    protected $active;
    protected $role;

    public function __construct()
    {        
      $this->db = new Database();   
      $this->orderType = 'Delivery';
      $this->active = 1;
      $this->role = 'Customer';
    }

    public function login($username, $password)
	{
		// $query = $this->db->query("SELECT ...");
		// $results = $this->db->getResults($query);
        $results = $this->getTableColumns('userid, username, password', 'user', "(username = '{$username}' AND password = '{$password}')");

        if (!is_array($results) || count($results) == 0) {
            $_SESSION['logged_in'] = false;
  
            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
            }
        } else {
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $results[0];
        }
        return $_SESSION['logged_in'];
    }

    public function logout()
    {  
        session_unset();
        session_destroy();
        return true;
    }

    public function getCustomerName($id) {
        $customerName = $this->getTableColumns('customer_name', 'customer', "userid = '{$id}'");
        return !empty($customerName) ? htmlspecialchars($customerName[0]['customer_name']) : 'Unknown User';
    }

    public function getTableColumns($columns, $table, $condition) {
        $sql = "SELECT {$columns} FROM {$table} WHERE {$condition}";
        $result = $this->db->getResults($this->db->query($sql));
        return $result ? $result : [];
    }

    public function getMaxTableNumberForDate($table, $numberColumn, $dateColumn, $date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', strtotime($date));
        }
        $sql = "SELECT MAX($numberColumn) AS max_number FROM {$table} WHERE Date({$dateColumn}) = '{$date}'";
        $query = $this->db->query($sql);
        $results = $this->db->getResults($query);

        return $results[0]['max_number'] ? $results[0]['max_number'] + 1 : 1;
    }

    // Get list of employees based on the order_type
    public function autoAssignEmployeeToOrder($order_type) {
        $roleId = $order_type == 'Delivery' ? 4 : [1, 2];
        $roleCondition = is_array($roleId) ? "IN (" . implode(',', $roleId) . ")" : "{$roleId}";
        
        $results = $this->getTableColumns('employeeid', 'employee', "roleid = {$roleCondition}");

        return $results[array_rand($results)]['employeeid'];
    }

    public function generateOrder() {
        $orderNumber = $this->getMaxTableNumberForDate('order_table', 'order_number', 'order_datetime');
        $orderType = $this->orderType;
        $orderDateTime = date('Y-m-d H:i:s');
        $employeeId = $this->autoAssignEmployeeToOrder($orderType);

        $order = [
                'order_number' => $orderNumber, 
                'order_type' => $orderType, 
                'order_datetime' => $orderDateTime,
                'employeeid' => $employeeId
            ]
        ;
        $orderId = $this->db->insert($order, 'order_table');

        return [$orderId, $orderNumber];
    }

    public function addOrder($orders) {
        [$orderId, $orderNumber] = $this->generateOrder();

        foreach ($orders as $order) {
            $data = [
                'menu_itemid' => $order['menuid'],
                'orderid' => $orderId,
                'menu_item_quantity' => $order['quantity']
            ];
            $this->db->insert($data, 'contain');
        }

        return $orderNumber;
    }

    public function alterOrder($orderid, $orderType) {
        $sql = "UPDATE order_table SET order_type = '{$orderType}' WHERE orderid = '{$orderid}'";

        $this->db->query($sql);
    }

    public function addTransaction($transaction, $userId) {
        // order transaction variables
        $orderNumber = $transaction['orderNumber'];
        $orderType = $transaction['orderType'];
        $paymentType = $transaction['paymentType'];

        // order transaction summary variables
        $subTotal = $transaction['subTotal'];
        $tip = $transaction['tip'];
        $tax = $transaction['taxCost'];
        $total = $transaction['total'];

        // delivery variables
        $deliveryDateTime = date("Y-m-d H:i:s");
        $houseNumber = $transaction['houseNumber'];
        $streetNumber = $transaction['streetNumber'];
        $streetName = $transaction['streetName'];
        $postalCode = $transaction['postalCode'];
        $specialInstructions = $transaction['specialInstructions'];

        if ($userId !== "") {
            $customerid = $this->getTableColumns('customerid', 'customer', "userid =  '{$userId}'")[0]['customerid'];
        }

        $transactionNumber = $this->getMaxTableNumberForDate('order_transaction', 'transaction_number', 'transaction_datetime');
        $transactionDateTime = date("Y-m-d H:i:s");
        $deliveryNumber = $this->getMaxTableNumberForDate('delivery', 'delivery_number', 'delivery_datetime');
        
        // get associated orderid
        $result = $this->getTableColumns('orderid', 'order_table', "(order_number = {$orderNumber} AND Date(order_datetime) = Date('{$transactionDateTime}'))");
        $orderid = $result[0]['orderid'];

        // Encrypted data
        $cardNumber = json_encode($this->db->encrypt($transaction['cardNumber'], $orderid));
        $expiryDate = json_encode($this->db->encrypt($transaction['expiryDate'], $orderid));
        $cvv = json_encode($this->db->encrypt($transaction['cvv'], $orderid));

        try {
            // insert to order_transaction table
            $order_transaction = [
                'transaction_number' => $transactionNumber,
                'payment_type' => $paymentType,
                'transaction_datetime' => $transactionDateTime,
                'card_number' => $cardNumber,
                'expiry_date' => $expiryDate,
                'cvv' => $cvv,
                'orderid' => $orderid
            ];
            $this->db->insert($order_transaction, 'order_transaction');
    
            // insert to order_transaction_summary table
            $order_transaction_summary = [
                'transaction_number' => $transactionNumber,
                'transaction_datetime' => $transactionDateTime,
                'tax' => $tax,
                'tip' => $tip,
                'sub_total' => $subTotal,
                'total' => $total
            ];
            $this->db->insert($order_transaction_summary, 'order_transaction_summary');
    
            // Modify order orderType
            $this->alterOrder($orderid, $orderType);

            // if order is delivery, insert record to delivery table
            if ($transaction['orderType'] == 'DELIVERY') {
                $delivery = [
                    'delivery_datetime' => $deliveryDateTime,
                    'delivery_number' => $deliveryNumber,
                    'orderid' => $orderid,
                    'customerid' => $customerid,
                    'delivery_status' => 'PENDING',
                    'house_number' => $houseNumber,
                    'street_number' => $streetNumber,
                    'street_name' => $streetName,
                    'postal_code' => $postalCode,
                    'special_instructions' => $specialInstructions
                ];
                $this->db->insert($delivery, 'delivery');
            }
            return true;
        } catch (Exception $e) {
            print_r($e);
            return false;
        }
    }

    public function getTransactionDetails($userId) {
        // $customerId = $this->getTableColumns('customerid', 'customer', "userid = '{$userId}'")[0]['customerid'];
        $result = $this->getTableColumns('customerid, customer_phone_number', 'customer', "userid = '{$userId}'")[0];
        $customerId = $result['customerid'];
        $customerPhoneNumber = $result['customer_phone_number'];

        // Retrieve customer address
        $addressIds = $this->getTableColumns('addressid', 'customer_has_address', "customerid = '{$customerId}'");

        $addressDetails = [];
        foreach ($addressIds as $address) {
            $addressId = $address['addressid'];
            
            // Retrieve the address information
            $result = $this->getTableColumns('house_number, postal_code', 'address', "addressid = '{$addressId}'")[0];
            $houseNumber = $result['house_number'];
            $postalCode = $result['postal_code'];

            // Retrieve address details
            $result = $this->getTableColumns('street_number, street_name', 'address_details', "(house_number = '{$houseNumber}' AND postal_code = '{$postalCode}')")[0];
            $streetNumber = $result['street_number'];
            $streetName = $result['street_name'];

            $addressDetails[] = [
                'house_number' => $houseNumber,
                'street_number' => $streetNumber,
                'street_name' => $streetName,
                'postal_code' => $postalCode,
            ];
        };

        // Retrieve customer payment information
        $paymentDetails = $this->getTableColumns('card_number, expiry_date, cvv', 'has_payment_information', "customerid = '{$customerId}'");
        $paymentInfos = [];
        foreach ($paymentDetails as $paymentMethod) {
            $encryptedCardNumber = $paymentMethod['card_number'];
            $encryptedExpiryDate = $paymentMethod['expiry_date'];
            $encryptedCVV = $paymentMethod['cvv'];
            
            $cardNumber = $this->db->decrypt($encryptedCardNumber, $userId);
            $expiryDate = $this->db->decrypt($encryptedExpiryDate, $userId);
            $cvv = $this->db->decrypt($encryptedCVV, $userId);

            $paymentInfos[] = [
                'card_number' => $cardNumber,
                'expiry_date' => $expiryDate,
                'cvv' => $cvv,
            ];
        }

        return [
            'phone_number' => $customerPhoneNumber,
            'addresses' => $addressDetails, 
            'payment_infos' => $paymentInfos,
        ];
    }

    function addReservation($reservation, $userId) {
        $reservation = $reservation[0];
        $partySize = $reservation['size'];
        $reservationDate = $reservation['date'];
        $reservationTime = $reservation['time'];
        $customerName = $reservation['name'];
        $customerPhoneNumber = $reservation['phone'];
        $customerId = "";

        // If customer is logged in set customer ID
        if ($userId != "") {
            $customerId = $this->getTableColumns('customerid', 'customer', "userid = '{$userId}'")[0]['customerid'];
        }

        // Concatenate and format reservation start and end date time
        $dateTimeString = $reservationDate . ' ' . $reservationTime;
        $reservationDateTime = DateTime::createFromFormat('Y-m-d H:i', $dateTimeString);
        $reservationEndDateTime = clone $reservationDateTime;
        $reservationEndDateTime = $reservationEndDateTime->add(new DateInterval('PT1H30M'));
        $reservationDateTime = $reservationDateTime->format('Y-m-d H:i:s');
        $reservationEndDateTime = $reservationEndDateTime->format('Y-m-d H:i:s');

        // Generate reservation number
        $reservationNumber = $this->getMaxTableNumberForDate('reservation', 'reservation_number', 'reservation_datetime', $reservationDateTime);

        // Get available tables
        $availableTable = $this->getAvailableTable($partySize, $reservationDateTime);

        $tableId = $availableTable['tableid'];
        $tableNumber = $availableTable['table_number'];


        $reservation_table = [
            'reservation_number' => $reservationNumber,
            'party_size' => $partySize,
            'reservation_datetime' => $reservationDateTime,
            'reservation__end_datetime' => $reservationEndDateTime,
            'customer_name' => $customerName,
            'customer_phone_number' => $customerPhoneNumber,
            'customerid' => $customerId,
            'tableid' => $tableId
        ];

        $this->db->insert($reservation_table, 'reservation');

        return ['reservation_number' => $reservationNumber, 'table_number' => $tableNumber, 'reservation_datetime' => $reservationDateTime, 'reservation_end_datetime' => $reservationEndDateTime];
    }

    public function getReservationDetails($userId) {
        // Get customer ID
        $results = $this->getTableColumns('customer_name, customer_phone_number', 'customer', "userid =  '{$userId}'");
        $customerName = $results[0]['customer_name'];
        $customerPhoneNumber = $results[0]['customer_phone_number'];

        return ['customerName' => $customerName, 'customerPhoneNumber' => $customerPhoneNumber];
    }

    // public function getAvailableTable($dateTime, $size) {
    public function getAvailableTable($size, $dateTime) {
        // Check tables that meet reservation size
        $tables = $this->getTableColumns('tableid, table_number', 'restaurant_table', "seating_capacity >= '{$size}'");
        
        // Check which tables are reserved
        $availableTables = [];
        foreach ($tables as $table) {
            $reservation = $this->getTableColumns('reservation_number, tableid', 'reservation', "(reservation_datetime = '{$dateTime}' AND tableid = '{$table['tableid']}')");
            // $reservation = $this->getTableColumns('reservation_number, tableid', 'reservation', "(reservation_datetime < '{$end}' AND reservation_end_datetime > '{$dateTime}')AND tableid = '{$table['tableid']}'");
            
            if (empty($reservation)) {
                $availableTables[] = $table;
            }
        }

        return $availableTables[array_rand($availableTables)];
    }

    public function getMenuList() {
        $sql = "SELECT * FROM menu_item";
        
		$query = $this->db->query($sql);
		$results = $this->db->getResults($query);

        return $results;
    }

    public function getMenuItem($menuid) {
        $result = $this->getTableColumns('menu_item_name, menu_description, menu_price', 'menu_item', "menu_itemid = '{$menuid}'");
        $menuName = $result[0]['menu_item_name'];
        $menuDescription = $result[0]['menu_description'];
        $menuPrice = $result[0]['menu_price'];

        return [$menuName, $menuDescription, $menuPrice];
    }

    public function getInventoryItems() {
        $sql = "SELECT item_name, item_quantity, category FROM inventory_item";
        $results = $this->db->getResults($this->db->query($sql));

        return $results;
    }

    public function getItemDetails() {
        // $sql = "SELECT * FROM inventory_item";
        $sql = "SELECT inventory.inventoryid, inventory_item.* FROM inventory JOIN inventory_item ON inventory.item_name = inventory_item.item_name ORDER BY inventoryid";
        $items = $this->db->getResults($this->db->query($sql));

        $sql = "SELECT DISTINCT category FROM inventory_item";
        $categories = $this->db->getResults($this->db->query($sql));

        // return [$items, $categories];
        return [$items, $categories];
    }

    public function getSupplierNames() {
        $sql = "SELECT DISTINCT supplier_name FROM supplier";
        $results = $this->db->getResults($this->db->query($sql));

        return $results;
    }

    public function addOrderSupply($orders) {
        $supplyOrderId = $this->getMaxTableNumberForDate('supply_order_details', 'supply_orderid', 'supply_order_datetime');
        $supplyOrderDateTime = date("Y-m-d H:i:s");

        $supply_order_details = [
            'supply_orderid' => $supplyOrderId,
            'supply_order_datetime' => $supplyOrderDateTime
        ];
        $this->db->insert($supply_order_details, 'supply_order_details');

        foreach ($orders as $order) {
            $itemName = $order['itemName'];
            $costPerUnit = $order['unitPrice'];
            $quantityOrdered = $order['quantity'];
            $totalCost = $order['totalCost'];
            $supplierName = $order['supplier'];
            $inventoryId = $this->getTableColumns('inventoryid', 'inventory', "item_name = '{$itemName}'")[0]['inventoryid'];
            $supplierId = $this->getTableColumns('supplierid', 'supplier', "supplier_name = '{$supplierName}'")[0]['supplierid'];

            $supply_order = [
                'inventoryid' => $inventoryId,
                'supplierid' => $supplierId,
                'supply_orderid' => $supplyOrderId,
                'cost_per_unit' => $costPerUnit,
                'quantity_ordered' => $quantityOrdered,
                'total_cost' => $totalCost,
                'supply_order_datetime' => $supplyOrderDateTime
            ];
            $this->db->insert($supply_order, 'supply_order');
        };

        return $supplyOrderId;
    }

    public function getDeliveries() {
        $deliveries = $this->getTableColumns('*', 'delivery', "(Date('delivery_datetime') = CURDATE() AND delivery_status = 'Pending')");
        // $deliveries = $this->getTableColumns('*', 'delivery', "(delivery_status = 'Pending')");
        $deliveryList = [];

        foreach($deliveries as $delivery) {
            $sql = "SELECT SUM(menu_item_quantity) FROM contain WHERE orderid = '{$delivery['orderid']}'";
            $itemCount = $this->db->getResults($this->db->query($sql))[0]['SUM(menu_item_quantity)'];

            $address = "{$delivery['house_number']} {$delivery['street_number']} {$delivery['street_name']}, {$delivery['postal_code']}";

            $data = [
                'deliveryNumber' => $delivery['delivery_number'],
                'address' => $address,
                'itemCount' => $itemCount,
            ];

            array_push($deliveryList, $data);
        }

        return $deliveryList;
    }

    public function updateDelivery($deliveryNumber) {
        $delivery_datetime = date('Y-m-d H:i:s');

        $sql = "UPDATE delivery SET delivery_status = 'Delivered', delivery_datetime = '{$delivery_datetime}' WHERE delivery_number = '{$deliveryNumber}'";

        $this->db->query($sql);
    }

    public function getOrderRequests() {
        $sql = "SELECT so.* FROM supply_order so WHERE NOT EXISTS (SELECT 1 FROM supply_order_details sod WHERE (sod.supply_orderid = so.supply_orderid AND sod.supply_order_datetime = so.supply_order_datetime)AND sod.order_status <> 'Waiting for Approval')";
        $supplyOrderIds = $this->db->getResults($this->db->query($sql));

        $supplyOrders = [];
        foreach ($supplyOrderIds as $supplyOrderId) {
            $inventoryId = $supplyOrderId['inventoryid'];
            $supplierId = $supplyOrderId['supplierid'];
            $costPerUnit = $supplyOrderId['cost_per_unit'];
            $quantityOrdered = $supplyOrderId['quantity_ordered'];
            $totalCost = $supplyOrderId['total_cost'];
            $supplyOrderDateTime = $supplyOrderId['supply_order_datetime'];

            $itemName = $this->getTableColumns('item_name', 'inventory', "inventoryid = '{$inventoryId}'")[0]['item_name'];
            $supplierName = $this->getTableColumns('supplier_name', 'supplier', "supplierid = '{$supplierId}'")[0]['supplier_name'];

            $key = $supplyOrderId['supply_orderid'] . '-' . $supplyOrderDateTime;

            if (!isset($supplyOrders[$key])) {
                $supplyOrders[$key] = [
                    'supplyOrderId' => $supplyOrderId['supply_orderid'],
                    'supplyOrderDateTime' => $supplyOrderDateTime,
                    'items' => [],
                    'totalCost' => 0,
                ];
            }

            $supplyOrders[$key]['items'][] = [
                'itemName' => $itemName,
                'supplierName' => $supplierName,
                'costPerUnit' => $costPerUnit,
                'quantityOrdered' => $quantityOrdered,
                'totalCost' => $totalCost,
            ];

            $supplyOrders[$key]['totalCost'] += $totalCost;
        }

        return $supplyOrders;
    }

    function updateOrderRequest($supplyOrders, $status) {
        foreach ($supplyOrders as $order) {
            $supplyOrderId = $order['orderId'];
            $supplyOrderDateTime = $order['orderDateTime'];
            
            $sql = "UPDATE supply_order_details SET order_status = '{$status}' WHERE supply_orderid = '{$supplyOrderId}' AND supply_order_datetime = '{$supplyOrderDateTime}'";
            $this->db->query($sql);
        }
        return true;
    }

    public function registerCustomer($customerDetails) {
        $encryptionKey = $this->db->generateKey();
        $username = $customerDetails['username'];
        $customerName = $customerDetails['customerName'];
        $password = $customerDetails['password'];
        $phoneNumber = $customerDetails['phoneNumber'];
        $houseNumber = $customerDetails['houseNumber'];
        $streetNumber = $customerDetails['streetNumber'];
        $streetName = $customerDetails['streetName'];
        $postalCode = $customerDetails['postalCode'];
        $paymentMethod = $customerDetails['paymentMethod'];
        $cardType = $customerDetails['cardType'];
        $cardNumber = $customerDetails['cardNumber'];
        $expiryDate = $customerDetails['expiryDate'];
        $cvv = $customerDetails['cvv'];

        
        switch ($paymentMethod) {
            case "1":
                $paymentMethod = "Debit Card";
                break;
                
            default:
                $paymentMethod = "Credit Card";
                break;
        }
            
        switch ($cardType) {
            case "1":
                $cardType = "Mastercard";
                break;

            default:
                $cardType = "Visa";
                break;
        }

        try {
            // Insert data to user table
            $user = [
                'username' => $username,
                'name' => $customerName,
                'password' => $password,
                'active' => $this->active,
                'role' => $this->role,
            ];
    
            $userId = $this->db->insert($user, 'user');
    
            // Insert data to customer table
            $customer = [
                'customer_name' => $customerName,
                'customer_phone_number' => $phoneNumber,
                'userid' => $userId,
            ];
    
            $customerId = $this->db->insert($customer, 'customer');
            
            if ($postalCode !== "") {
                // Insert data to address table
                $address = [
                    'house_number' => $houseNumber,
                    'postal_code' => $postalCode,
                ];
                
                $addressId = $this->db->insert($address, 'address');
    
                // Insert data to address_details table
                $address_details = [
                    'house_number' => $houseNumber,
                    'postal_code' => $postalCode,
                    'street_number' => $streetNumber,
                    'street_name' => $streetName,
                ];
    
                $this->db->insert($address_details, 'address_details');
    
                // Insert data to customer_has_address table
                $customer_has_address = [
                    'customerid' => $customerId,
                    'addressid' => $addressId,
                ];
                $this->db->insert($customer_has_address, 'customer_has_address');
            }

            if ($cardNumber !== "") {
                $cardNumber = json_encode($this->db->encrypt($cardNumber, $encryptionKey));
                $expiryDate = json_encode($this->db->encrypt($expiryDate, $encryptionKey));
                $cvv = json_encode($this->db->encrypt($cvv, $encryptionKey));
                // Insert data to has_payment_information table
                $has_payment_information = [
                    'card_number' => $cardNumber,
                    'customerid' => $customerId,
                    'payment_method' => $paymentMethod,
                    'card_type' => $cardType,
                    'expiry_date' => $expiryDate,
                    'cvv' => $cvv,
                ];
        
                $this->db->insert($has_payment_information, 'has_payment_information');
            }

            return true;
        } catch (Exception $e){
            // return false;
            return $e;
        }
    }

    public function verifyUser($username, $password) {
        $result = $this->getTableColumns('*', 'user', "(username = '{$username}' AND password = '{$password}')");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function resetPassword($username, $oldPassword, $newPassword) {
        $sql = "UPDATE user SET password = '{$newPassword}' WHERE (username = '{$username}' AND password = '{$oldPassword}')";
        $query = $this->db->query($sql);
    }

    public function getCustomerInfo() {}
}