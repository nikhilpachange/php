<?php

class Database {

    private $db_host = "localhost"; // Database host
    private $db_user = "root";      // Database username
    private $db_pass = "";          // Database password
    private $db_name = "testing";   // Database name

    private $result = array();      // Array to store query results
    private $mysqli = null;         // MySQLi connection object
    private $conn = false;          // Connection status

    public function __construct() {
        if (!$this->conn) {
            $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

            if ($this->mysqli->connect_error) {
                array_push($this->result, $this->mysqli->connect_error);
                return false;
            } else {
                $this->conn = true;
                return true;
            }
        }
    }

    // Insert method
    public function insert($table, $params) {
        if ($this->conn) {
            $columns = implode(", ", array_keys($params));
            $values = implode("', '", array_values($params));
            $sql = "INSERT INTO $table ($columns) VALUES ('$values')";

            if ($this->mysqli->query($sql)) {
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            array_push($this->result, "No database connection");
            return false;
        }
    }

    // Method to check results
    public function getResult() {
        return $this->result;
    }

    // Destructor to close connection
    public function __destruct() {
        if ($this->conn) {
            $this->mysqli->close();
            $this->conn = false;
        }
    }
}

?>
