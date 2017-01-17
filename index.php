<?php
require_once 'core/init.php';

//echo phpversion();
//echo Config::get('mysql/host'); //expect 127.0.0.1
//$db = new DB(); //won't work for singleton pattern

//DB::getInstanceDB(); //create a single database connection
// $user = DB::getInstanceDB()->makeQuery("SELECT username FROM users WHERE username = ? or username = ?", array(
//   "billy",
//   "bob")
// );

$user = DB::getInstanceDB()->get('users', array('username', '=','alex'));
//$user = DB::getInstanceDB()->get('users', array('username', '=','billy'));

//$user = DB::getInstanceDB()->makeQuery("SELECT username FROM users");

//echo "Were here!<br>"; //debug
//var_dump($user);
//if($user->getError()){
if(!$user->getCount()){
  echo 'Error. Please check query syntax, spelling, and whether table data exists.<br>';
} else{
  echo 'ΟΚ!<br>';  //user must exist to pass this
}
