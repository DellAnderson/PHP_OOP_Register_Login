<?php
//this init file will be included on every page we create as 'require_once('core/init.php')'
session_start(); //required to set sessions (login)
//db password is stored outside of project this file contains variable $PASSWORD
require_once(../../settings/mysql_setting.php) //two levels up

//set global config array of settings used by config class_alias
$GLOBALS['config'] = array( //storage area
  'mysql' => array(
    'host'=>'127.0.0.1', //if used localhost, must do DNS lookup
    'username'=>'php_oop_db',
    'password'=>$PASSWORD, //note that his variable is set outside git repo
    'db'=>'youtube'
  ),//mysql settings array
  'remember' => array(
    'cookie-name'=> 'hash',
    'cookie_expiry'=> 604800 //duration in seconds
  ), //cookie names & expiration for users
  'session' => array(
    'session_name'=>'user'
  ) //session name & token used
);

//autoload classes as required
spl_autoload_register(functoin($class){
  require_once 'classes/' . $ class . '.php';
});//pass in anonymous function that is run every time accessed

require_once 'functions/sanitize.php'; //can't autoload because not class
