<?php
session_start();  
class Database
{
    private $conn;
    private $masterKey; 

    public function __construct() 
    {
        require(dirname(__FILE__) . '/../config/config.php');

        $this->conn = mysqli_connect($config['db_host'],$config['db_user'], $config['db_pass'], $config['db_name']);
        $this->masterKey = getenv('MASTER_KEY');
    }

    public function getConn() {
        return $this->conn;
    }

    public function escapeString($string) {
        if ($string === '' || $string === null) {
            return 'NULL';
        }
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

        if (!$this->query($query)) {
            die("Error executing query: " . mysqli_error($this->conn));
        }

        $insertedId = mysqli_insert_id($this->conn);


        return $insertedId;
    }

    public function getResults($result) {
        $results = [];
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        return $results;
    }

    public function generateKey() {
        $key = openssl_random_pseudo_bytes(16);
        $cipher = "aes-256-cbc";
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encryptedKey = openssl_encrypt($key, $cipher, $this->masterKey, 0, $iv);

        $tag = bin2hex(openssl_random_pseudo_bytes(16));

        $query = "INSERT INTO encryption_keys (encrypted_key, iv, tag) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $encryptedKey, $iv, $tag);
        $stmt->execute();

    return $key;
    }

    public function getKey($id) {
        $query = "SELECT encrypted_key, iv FROM encryption_keys WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($encryptedKey, $iv);
        $stmt->fetch();

        $key = openssl_decrypt($encryptedKey, "aes-256-cbc", $this->masterKey, 0, $iv);
        return $key;
    }

    public function encrypt($data, $key) {
        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $tag = '';
        
        if (empty($data) || empty($key)) {
            echo "Data or key is empty!";
            return null;
        }

        $ciphertext = openssl_encrypt($data, $cipher, $key, 0, $iv, $tag);
        if ($ciphertext === false) {
            echo "Encryption failed!";
            return null;
        }

        return json_encode([
            'ciphertext' => $ciphertext,
            'iv' => base64_encode($iv), 
            'tag' => base64_encode($tag)
        ]);
    }

    public function decrypt($data, $id) {
        $data = trim(stripslashes($data), '"');
        $decodedData = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON decode error: ' . json_last_error_msg();
            die();
        }

        if (!isset($decodedData['ciphertext'], $decodedData['iv'], $decodedData['tag'])) {
            echo "Invalid encrypted data!";
            return null;
        }

        $ciphertext = $decodedData['ciphertext'];
        $iv = base64_decode($decodedData['iv']);
        $tag = base64_decode($decodedData['tag']);
        
        $key = $this->getKey($id);
        $cipher = "aes-128-gcm";

        return openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv, $tag);
    }
}