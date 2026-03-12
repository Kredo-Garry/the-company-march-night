<?php

class Database {
    private $server_name = "localhost"; //127.0.0.1 (XAMPP/MAMP)
    private $username = "root";
    private $password = "root"; //the detaul password for MAMP, empty for XAMPP
    private $db_name = "the_company_march";
    protected $conn;

    public function __construct(){
        $this->conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);

        # Check the connection
        if ($this->conn->connect_error) {
            die("Unable to connect to the database: " . $this->conn->connect_error);
        }
    }
}