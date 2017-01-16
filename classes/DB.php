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
      //echo "created one new instance of DB connection<br>"; //debug only
      //echo "connected"; //debug only
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

/**
 *query to securely prepare SQL statements & bind parameters to help
 *prevent SQL injection
 *returns results as an object
 * @param  str $sql    = the sql query
 * @param  array  $params = array of values to be bound to the query
 * @return [type]         [description]
 */
  public function query($sql, $params = array()){
    $this->error = false; //reset false so don't return error from previous query
    //assign & check query prep
    if($this->query = $this->pdo->prepare($sql)){
      echo "Successful preparation<br>"; //verifies prepared properly //debug
      var_dump($sql); //debug
      echo "<br>"; //debug
      $x = 1;//counter
      if(count($params)){ //if there are parameters
        foreach($params as $param){
          //assign array values to question marks (in order)
          echo "(In Foreach loop: ";
          $this->query->bindValue($x, $param); //bind by position(x), value
          echo " ". $x . ", " . $param .")<br>"; //debug
          $x++;
        }
      }
      var_dump($this->query); //debug
      echo "<br>"; //debug
      //execute query with or without parameters
      if($this->query->execute()){
        echo "we've executed!<br>";  //debug
        $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
        $this->count = $this->query->rowCount();//PDO method
        echo "Success";//query has been executed successfully by PDO
      } else {
        $this->error = true;
      }

    }
    return $this; //allows chaining methods by returning obj we are working with
  }

  public function action($action, $table, $where = array()){
    if(count($where) === 3){//must have 3 operators
      $field    = '$where[0]';
      $operator = '$where[1]';
      $value    = '$where[2]';
      //https://dev.mysql.com/doc/refman/5.5/en/comparison-operators.html
      //list valid operators - can expand later to items from
      $valid_operators = array('=', '>', '<', '>=', '<=');
      //check if operator is inside the operators array
      if(in_array($operator, $valid_operators)){
        echo "Truth";
        //question mark allows us to bine value (?)
        $sql = "{$action} FROM {$table} WHERE {$field}{$operator} ?";
      }
    }
  }



  /**
   * somewhat Silly method to return error property (could just make propery public!)
   * @return bool = success or failure of query
   */
  public function error(){
    return $this->error;
  }
}
