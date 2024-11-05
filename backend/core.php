<?php
session_start();
require_once(dirname(__FILE__)."/../config/config.php");

class Core
{
    /** @var Database */
    protected $db;
    protected $orderType;
    const TAX_RATE = 0.12;

    public function __construct()
    {        
      $this->db = new Database();   
      $this->orderType = 'Delivery';
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

    public function calculateTax($subTotal) {
        return $subTotal * self::TAX_RATE;
    }

    public function calculateTotal($tax, $tip, $subTotal) {
        return $tax + $tip + $subTotal;
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
        $this->db->insert($order, 'order_table');

        // get the generated orderid
        $result = $this->getTableColumns('orderid', 'order_table', "(order_number = '{$orderNumber}' AND order_datetime = '{$orderDateTime}')");

        return [$result[0]['orderid'], $orderNumber];
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

    public function addTransaction($transaction) {
        $transaction = $transaction[0];
        $orderNumber = $transaction['orderNumber'];
        $paymentType = $transaction['paymentType'];
        $subTotal = $transaction['subTotal'];
        $tip = $transaction['tip'];
        $transactionNumber = $this->getMaxTableNumberForDate('order_transaction', 'transaction_number', 'transaction_datetime');
        $transactionDateTime = date("Y-m-d H:i:s");
        $tax = $this->calculateTax($subTotal);
        $total = $this->calculateTotal($tax, $tip, $subTotal);
        
        // get associated orderid
        $result = $this->getTableColumns('orderid', 'order_table', "(order_number = {$orderNumber} AND Date(order_datetime) = Date('{$transactionDateTime}'))");
        $orderid = $result[0]['orderid'];

        // insert to order_transaction table
        $order_transaction = [
            'transaction_number' => $transactionNumber,
            'payment_type' => $paymentType,
            'transaction_datetime' => $transactionDateTime,
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

    }

    function addReservation($reservation) {
        $reservation = $reservation[0];
        $partySize = $reservation['partySize'];
        $reservationDate = $reservation['reservationDate'];
        $reservationTime = $reservation['reservationTime'];
        $tableNumber = $reservation['tableNumber'];
        $userId = $_SESSION['user']['userid'];
        
        // Concatenate and format reservation date and time
        $dateTimeString = $reservationDate . ' ' . $reservationTime;
        $reservationDateTime = DateTime::createFromFormat('Y-m-d H:i', $dateTimeString)->format('Y-m-d H:i:s');

        $reservationNumber = $this->getMaxTableNumberForDate('reservation', 'reservation_number', 'reservation_datetime', $reservationDateTime);

        // Get customer ID
        $results = $this->getTableColumns('customerid', 'customer', "userid =  '{$userId}'");
        $customerid = $results[0]['customerid'];

        // Get table ID
        $results = $this->getTableColumns('tableid', 'restaurant_table', "table_number = '{$tableNumber}'");
        $tableId = $results[0]['tableid'];

        $reservation_table = [
            'reservation_number' => $reservationNumber,
            'party_size' => $partySize,
            'reservation_datetime' => $reservationDateTime,
            'customerid' => $customerid,
            'tableid' => $tableId
        ];

        $this->db->insert($reservation_table, 'reservation');
    }

    public function getMenuList() {
<<<<<<< HEAD
        // $sql = "SELECT * FROM menu";
=======
        $sql = "SELECT * FROM menu_item";
>>>>>>> 5e485ecd997181ddd4b926fb007e8f2bf4ffd68c
        
		// $query = $this->db->query($sql);
		// $results = $this->db->getResults($query);

<<<<<<< HEAD


        // return $results;
=======
        return $results;
>>>>>>> 5e485ecd997181ddd4b926fb007e8f2bf4ffd68c
    }
    public function getCustomerInfo() {}
}