<?php
/**
 * Author: Ahmad Raza
 * Description: Connects to DB
 */

class Connection{

    /**
     * @var Connection
     */    
    private static $instance = null;
    
    /**
     * @var mysqli class object
     */    
    private static $conn = null;

    private function __construct() 
    {
        $servername = "localhost"; // DBMS server host
        $username = "root"; // DBMS server username
        $password = "";    // DBMS server password
        $db = "codechallenge"; /// DB name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $db);
    
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        self::$conn = $conn;
    }

    /**
     * @return Connection
     */    
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }  

    /**
     * @return mysqli class object
     */    
    public function getConnectionHandle()
    {
        return self::$conn;
    }
}

