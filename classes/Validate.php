<?php
class Validate{
    private $passed = false;
    private $errors = array();
    private $_db = null;

    public function __construct(){
      $this->_db = DB::getInstanceDB();
    }

    public function check($http_request_result, $fields = array()){
      foreach($fields as $field => $rules){
        foreach($rules as $rule => $rule_value){
          //echo "{$field}{$rule} must be {$rule_value}<br>"; //debug
          $value = $http_request_result[$field];
          //echo $value . $rule_value . "<br>"; //debug
          //check if required $value is present
          if($rule === 'required' && empty($value)){
            $this->addError("{$field} is required"); //TODO this uses field name, change?
          } else {

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
