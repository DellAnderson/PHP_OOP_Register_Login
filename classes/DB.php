<?php
//create database class using singleton pattern, can use this class anywhere
// private properties style per http://www.php-fig.org/psr/psr-2/
// " no underscore begin private properties
// " 4.2 one property per statement
class DB{
  private static $instance = null;
  private $pdo; //storage of instantiated PDO object
  private $query; //last query executed
  private $error = false; //if query failed
  private $results; //result set from query
  private $count = 0;  //very important to know number of results

  private function __construct(){
    try{
      //unlike C, variables in functions are by default private scope
      $dbhost = Config::get('mysql/host');
      $dbname = Config::get('mysql/db');
      $username = Config::get('mysql/username');
      $password = Config::get('mysql/password');
      $dsn = 'mysql:host='.$dbhost.';dbname='.$dbname;
      //PDO(datasource name, username, password, [options] )
      $this->pdo = new PDO($dsn,$username,$password);
      echo "created one new instance of DB connection<br>";
      echo "connected";
    }catch(PDOException $e){
      die($e->getMessage());
    }
  }

  public static function getInstanceDB(){
    if(!isset(self::$instance)) { //false on second time function is run on page
      self::$instance = new DB();//static method so use 'self'
    }
    return self::$instance;

  }
}
