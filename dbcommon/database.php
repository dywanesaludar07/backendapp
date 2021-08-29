<?php 
   class Database{
        private $host = 'localhost';
        private $database_name = 'hatid_application';
        private $username = 'ehatidapp';
        private $password = 'Ehatidcourier2021';
        public $connection;
        public function Connection(){
            try {
                $this->connection = new PDO('mysql:host = "'.$this->host.'";dbname='. $this->database_name. ';charset=utf8', $this->username,$this->password);
            }catch (PDOException $exeception) {
                print($exeception->getMessage());
            }
            return $this->connection;
        }
    }