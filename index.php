<?php
require_once 'core/init.php';

//echo phpversion();
//echo Config::get('mysql/host'); //expect 127.0.0.1
//$db = new DB(); //won't work for singleton pattern

//DB::getInstanceDB(); //create a single database connection
$user = DB::getInstanceDB()->query("SELECT username FROM users WHERE username = ? or username = ?", array(
  "billy",
  "bob")
);

//$user = DB::getInstanceDB()->query("SELECT username FROM users");

echo "Were here!<br>";

if($user->error() == true){
  echo 'Query generated an error. Please check query syntax & spelling.';
} else{
  echo 'ΟΚ!';
}
