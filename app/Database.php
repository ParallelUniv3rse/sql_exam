<?php

class Database {
    public $connection;

    function __construct($host = "localhost", $username = "root", $password = "root", $dbname = "superdating") {
        $this->connection = $this->initDbConnection($host, $username, $password, $dbname);
    }

    private function initDbConnection($host, $username, $password, $dbname) {
        $conn = new mysqli($host, $username, $password, $dbname);
        /* check connection */
        if ($conn->connect_errno) {
            printf("Connect failed: %s\n", $conn->connect_error);
            exit();
        }
        return $conn;
    }

    public function query($query) {
        $result = $this->connection->query($query);
        if (!$result) {
            die("Database error: " . $this->connection->error);
        }
        if (is_bool($result)) {
            return $result;
        } else {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
}
