<?php
class DBClass {

    private $host = "50.62.209.51:3306";
    private $username = "lata";
    private $password = "clpl@123#"; 
    private $database = "clpl_emp"; 

    // private $host = "localhost";
    // private $username = "root";
    // private $password = "";
    // private $database = "clpl_emp";

    public $connection;

    // get the database connection
    public function getConnection(){

        $this->connection = null;

        try{
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->connection->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        }

        return $this->connection;
    }
}
?>