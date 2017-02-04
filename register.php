<?php
  require_once 'core/init.php'; //need autoloader EvWatcher

  //check if any data has been submitted (doesn't matter, valid or not)
  if(Input::exists()){ //we know we are using POST data hera, so defaults to POST
  //  echo 'Submitted';
    //echo Input::get('username');
    $validate = new Validate();
    //see also https://youtu.be/rWon2iC-cQ0?list=PLfdtiltiRHWF5Rhuk7k4UAU1_yLAZzhWc&t=839
    //array of arrays (rules for fields)
    $validation = $validate->check($_POST, array(
      'username' => array( //fieldname - may not want to reveal fieldnames in database
        'required'=> true,
        'min' => 2,
        'max' => 20,
        'unique' => 'users' //database table name
      ),
      'password' => array(
        'required' => true,
        'min'=>6
      ),
      'password_again' => array(
        'required' =>true,
        'matches' => 'password'
      ),
      'name' => array(
        'required'=> true,
        'min' =>2,
        'max' =>50
      )
    ));
    if($validation->passed()){
      echo 'Passed';
    } else{
      print_r($validation->errors());
    }

  }
?>

<form action = "" method = "post">

  <div class = "field"
    <label for "username">Username</label> <!--applies to id username-->
    <input type = "text" name = "username" id = "username" value = "" autocomplete = "off">
  </div>

  <div class= "field">
    <label for "password"> Choose a password</label> <!--applkies to id password-->
    <input type = "password" name = "password" id = "password">
  </div>

  <div class = "field">
    <label for "password_again"> Enter your password again</label>
    <input type = "password" name = "password_again" value = "" id - "password_again">
  </div>

  <div class = "field">
    <label for "name"> Enter your name</label>
    <input type = "text" name = "name" id = "name">
  </div>

  <input type = "submit" value = "Register">

</form>
