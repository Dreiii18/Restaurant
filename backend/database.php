<?php
  
class Database
{
    private $conn;

    public function __construct() 
    {
        require(dirname(__FILE__) . '/../config/config.php');

        $this->conn = mysqli_connect($config['db_host'],$config['db_user'], $config['db_pass'], $config['db_name']);
    }

    public function query($query)
    {
        return mysqli_query( $this->conn,$query);
    }

    public function insert($record, $table)
    {
        foreach ($record as $key => $value) {
            # code...
        }
        $query = "INSERT INTO ....";
        return $this->query($query);
    }

    public function getResults($result) {
        $results = [];
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        return $results;
    }
}