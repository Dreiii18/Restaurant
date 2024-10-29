<?php

require_once(dirname(__FILE__)."/../config/config.php");

class Core
{
    /** @var Database */
    protected $db;

    public function __construct()
    {        
      $this->db = new Database();               
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
        // $sql = "SELECT * FROM menu";
        
		// $query = $this->db->query($sql);
		// $results = $this->db->getResults($query);



        // return $results;
    }
    public function addOrder() {}
    public function getCustomerInfo() {}
}