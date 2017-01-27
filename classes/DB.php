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
  public function makeQuery($sql, $params = array()){
    $this->error = false; //reset false so don't return error from previous query
    //check query prep preparation & assign to query property
    if($this->query = $this->pdo->prepare($sql)){
      $x = 1;//counter
      if(count($params)){ //if there are parameters
        foreach($params as $param){
          //assign array values to question marks (in order)
          //binding parameters reduces possibility of SQL injection
          //is native to PDO, just abstracted a bit here (TODO change?)
          $this->query->bindValue($x, $param); //bind by position(x), value
          $x++;
        }
      }

      //execute query with or without parameters
      if($this->query->execute()){
        //echo "we've executed!<br>";  //debug
        $this->results = $this->query->fetchAll(PDO::FETCH_OBJ); //return table as OBJ
        $this->count = $this->query->rowCount();//PDO method
        //echo "Success<br><br>";//debug: query has been executed successfully by PDO
      } else {
        $this->error = true;
      }
    }
    return $this; //allows chaining methods by returning obj we are working with
  }

//optional method to make queries easier (helper method?)
  private function action($action, $table, $where = array()){
    if(count($where) === 3){//must have 3 operators
      $field    = $where[0];
      $operator = $where[1];
      $value    = $where[2];
      //https://dev.mysql.com/doc/refman/5.5/en/comparison-operators.html
      //list valid operators - can expand later to items from
      $valid_operators = array('=', '>', '<', '>=', '<=');

      if(in_array($operator, $valid_operators)){

        //TODO check action validity
        //question mark allows us to bind value (?)
        $sql = "{$action} FROM {$table} WHERE {$field}{$operator} ?";
        if(!$this->makeQuery($sql, array($value))->getError()){
          return $this; //if no error, return $this
          //to use with method to return result set
        }
      }
    }
    return false; //if error
  }

public function get($table, $where){
  return $this->action('SELECT *', $table, $where);
}

public function delete($table, $where){
  return $this->action('DELETE', $table, $where);
}

public function insert($table, $fields = array()){
  //if(count($fields)){
    $keys = array_keys($fields);
    $values = '';
    $x = 1;
    foreach($fields as $field){
      $values .= "?"; //make ?'s for values string creation
      if($x < count($fields)){
        $values .= ', '; //add comma if not at end
      }
      $x++;
    }
    //die($values);
    //create query with bound question marks with values want to insert
    $sql = "INSERT INTO users (`" . implode('`, `', $keys). "`) VALUES ({$values})";
    //echo $sql;
    //$fields will be bound to previously created question marks
    //if no error, return true
    if(!$this->makeQuery($sql, $fields)->getError()){
      return true; //allows us to check whether data inserted
    }
  //} //from if(count...)
  return false;
}

public function update($table, $id, $fields = array()){
  $set = '';
  $x = 1;

  foreach($fields as $name => $value){
    $set .= "{$name} = ?";  //bind using ? to avoid SQL injection
    if($x < count($fields)){
      $set .= ', ';
    }
    $x++;
  }
  //die($set);
  $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
  //die($sql);
  //perform query
  if(!$this->makeQuery($sql, $fields)->getError()){
    return true; //if no error
  }
  return false;
}

public function getResults(){
  return $this->results;
}

public function getFirst(){
  return $this->getResults()[0];
}
  /**
   * somewhat Silly method to return error property (could just make propery public!)
   * @return bool = success or failure of query
   */
  public function getError(){
    return $this->error;
  }

  public function getCount(){
    return $this->count;
  }
}
