<?php
// session_start();
require_once(dirname(__FILE__)."/../config/config.php");

class Core
{
    /** @var Database */
    protected $db;
    protected $orderType;

    public function __construct()
    {        
      $this->db = new Database();   
      $this->orderType = 'Delivery';
    }

    public function login($username, $password)
	{
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

    public function getCustomerInfo() {}
}