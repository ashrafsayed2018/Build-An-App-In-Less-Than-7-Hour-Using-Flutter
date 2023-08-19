<?php

define("HOST", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DB_NAME", "better_buys");

class Database
{
    private $connection;

    public function __construct()
    {
        $this->open_db_connection();
    }

    public function open_db_connection()
    {
        $this->connection = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME);

        if (mysqli_connect_error()) {
            die("connection_error: " . mysqli_connect_errno());
        }
    }

    public function query($sql)
    {
        $result = $this->connection->query($sql);

        if (!$result) {
            die("query failed: " . $sql);
        }

        return $result;
    }


    // fetch a list of data from the sql query result
    public function fetch_array($result)
    {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }
            return $result_array;
        }
    }

    // fetch single row 

    public function fetch_row($result)
    {
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    }


    // escape the string
    public function escape_value($value)
    {
        return $this->connection->real_escape_string($value);
    }


    public function last_insert_id()
    {
        return $this->connection->insert_id;
    }

    // close the database 

    public function close_connection()
    {
        $this->connection->close();
    }
}

$database = new Database();
