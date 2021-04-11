<?php
// $dsn = "mysql:host-localhost;dbname=twitter";
// $dbuser = "root";
// $dbpass = "";

// try {
//     $dbh = new PDO($dsn, $dbuser, $dbpass);
// } catch(PDOException $e) {
//     echo "Verbindung fehlgeschlagen".$e->getMessage();
// }


class Database {
   public $pdo;
   public static $instance;

   public function __construct() {
       $this->pdo=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);
   }

   public static function instance() {
       if(self::$instance == null) {
           self::$instance = new self;
       }
       return self::$instance;
   }

   public function __call($method, $args) {
       return call_user_func_array(array($this->pdo,$method),$args); 
   }
}
?>