<?php
class Validate{
    private $passed = false;
    private $errors = array();
    private $db = null;

    public function __construct(){
      //instantiate (if needed)  database handler
      $this->db = DB::getInstanceDB();
    }

    public function check($http_request_result, $fields = array()){
      foreach($fields as $field => $rules){
        foreach($rules as $rule => $rule_value){
          //echo "{$field}{$rule} must be {$rule_value}<br>"; //debug
          $value = trim($http_request_result[$field]); //trim whitespace
          $field = escape($field); //sanitize before output to error messages

          //echo $value . $rule_value . "<br>"; //debug
          //check if required $value is present
          if($rule === 'required' && empty($value)){
            $this->addError("{$field} is required"); //TODO this uses field name, change?
          } else if(!empty($value)) { //technically 'if' not required if passed above
              switch($rule) {
                case 'min':
                  if(strlen($value) < $rule_value){
                    $this->addError("{$field} must be a minimum of {$rule_value} characters." );

                  }
                break;
                case 'max':
                  if(strlen($value) > $rule_value){ //TODO change error message to avoid name of fields
                    $this->addError("{$field} must be a maximum of ($rule_value) characters.");
                  }
                break;
                case 'matches':
                  if($value != $http_request_result[$rule_value]){
                    $this-> addError("{$rule_value} must match {$field}");
                  }
                break;
                case 'unique':
                //TODO verify that this is not a security risk
                  //get db results for this field in this database
                  $existing_results = $this->db->get($rule_value, array($field, '=', $value));
                  if($existing_results->getCount()){ //if count is positive, means item already in db
                    $this->addError("{$field} already exists");
                  }
                break;
                default:

              }
          }
        }
      }

      if(empty($this->errors)){
        $this->passed = true; //we haven't stored any errors!
      }
      return $this; //so we can chain on things like 'passed'
    }

//function creates(?) an errors array
    private function addError($error){
      $this->errors[] = $error;
    }

//TODO simplify this mess!  Not sure why tutorial makes these methods
    public function errors(){
      return $this->errors;
    }

//TODO could simplify this - all this does is return above private property
    public function passed(){
      return $this->passed;
    }
}
