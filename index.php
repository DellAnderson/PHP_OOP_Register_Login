<?php
require_once 'core/init.php';

//$user = DB::getInstanceDB()->get('users', array('username', '=','alex'));
$user = DB::getInstanceDB()->makeQuery("SELECT * FROM users");

if(!$user->getCount()){
  echo 'Error. Please check query syntax, spelling, and whether table data exists.<br>';
} else{
  //echo 'ΟΚ!<br>';  //user must exist to pass this
  // foreach($user->getResults() as $user){
  //   echo $user->username, '<br>';
  echo $user->getFirst()->username;
  }
