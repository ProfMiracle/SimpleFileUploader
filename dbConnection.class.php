<?php
//class
class dbConnection{
    //mysqli_report(MYSQLI_REPORT_ERROR| MYSQLI_REPORT_STRICT);

    private $servername = "127.0.0.1";
    private $username = "username";
    private $password = "password";
    private $database = "database_name";

        protected function connect(){
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $con = new mysqli($this->servername, $this->username, $this->password, $this->database);
            //$con->setAttribute(MSQLI:: ATTR_DEFAULY_FETCH_MODE, MYSQLI::FETCH_ASSOC);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $con->set_charset("utf8mb4");
            return $con;
        }
    }
?>