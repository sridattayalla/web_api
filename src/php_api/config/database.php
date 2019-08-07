<?php

class Database{

    //database credentials
    private $server_name = "localhost";
    private $dbUserName = "root";
    private $password="";
    private $dbName = "employee";
    public $conn = null;

    //method to get databse connection
    public function getConnection(){
        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=".$this->server_name.";dbname=".$this->dbName, $this->dbUserName, $this->password);
            //echo 'success';
        }catch(PDOException $exception){
            //echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}