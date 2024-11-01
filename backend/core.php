<?php

require_once(dirname(__FILE__)."/../config/config.php");

class Core
{
    /** @var Database */
    protected $db;
    protected $order_type;

    public function __construct()
    {        
      $this->db = new Database();   
      $this->order_type = 'Delivery';
    }

    public function login($username, $password)
	{
		$query = $this->db->query("SELECT ...");
		$results = $this->db->getResults($query);

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

    public function getMenuList() {
        $sql = "SELECT * FROM menu_item";
        
		$query = $this->db->query($sql);
		$results = $this->db->getResults($query);

        return $results;
    }

    public function getOrderNumber() {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM order_table WHERE Date(order_datetime) = '$today'";
        $query = $this->db->query($sql);
        $results = $this->db->getResults($query);

        // Check if there is order_number within the day
        if (count($results) == 0) {
            // If no previous order_number then return 1 as default
            return 1;
        } else {
            // Get last order_number and return the incremented value
            $sql = "SELECT MAX(order_number) AS max_order_number FROM order_table WHERE Date(order_datetime) = '{$today}'";
            $query = $this->db->query($sql);
            $results = $this->db->getResults($query);
          
            return $results[0]['max_order_number'] + 1;
        }
    }

    // Get list of employees based on the order_type
    public function autoAssignEmployeeToOrder($order_type) {
        if ($order_type == 'Delivery') {
            // Get employees with roleid of 4 (Delivery Driver)
            $sql = "SELECT employeeid FROM employee WHERE roleid=4";
        }

        if ($order_type == 'Dine In') {
            // Get employees with roleid of either 1 (Manager) or 2 (Cashier)
            $sql = "SELECT employeeid FROM employee WHERE (roleid=1 OR roleid=2)";
        }

        $query = $this->db->query($sql);
        $results = $this->db->getResults($query);

        return $results[array_rand($results)]['employeeid'];
    }

    public function generateOrder() {
        $order_number = $this->getOrderNumber();
        $order_type = $this->order_type;
        $order_datetime = date('Y-m-d H:i:s');
        $employeeid = $this->autoAssignEmployeeToOrder($order_type);

        $order = [
                'order_number' => $order_number, 
                'order_type' => $order_type, 
                'order_datetime' => $order_datetime,
                'employeeid' => $employeeid
            ]
        ;
        $this->db->insert($order, 'order_table');

        // get the generated orderid
        $sql = "SELECT orderid FROM order_table WHERE (order_number = '{$order_number}' AND order_datetime = '{$order_datetime}')";
        $query = $this->db->query($sql);
        $result = $this->db->getResults($query);

        return $result[0]['orderid'];
    }

    public function addOrder($orders) {
        $orderid = $this->generateOrder();
        foreach ($orders as $order) {
            $data = [
                'menu_itemid' => $order['menuid'],
                'orderid' => $orderid,
                'menu_item_quantity' => $order['quantity']
            ];
            $this->db->insert($data, 'contain');
        }
    }
    public function getCustomerInfo() {}
}