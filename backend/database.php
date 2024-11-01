<?php
  
class Database
{
    private $conn;

    public function __construct() 
    {
        require(dirname(__FILE__) . '/../config/config.php');

        $this->conn = mysqli_connect($config['db_host'],$config['db_user'], $config['db_pass'], $config['db_name']);
    }

    public function escapeString($string) {
        return mysqli_real_escape_string($this->conn, $string);
    }

    public function query($query)
    {
        return mysqli_query( $this->conn,$query);
    }

    public function insert($record, $table)
    {
        $record = array_map([$this, 'escapeString'], $record);
        $columns = implode(', ', array_keys($record));
        $values = implode("', '", array_values($record));
        $query = "INSERT INTO $table ($columns) VALUES ('$values')";
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