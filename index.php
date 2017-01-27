<?php
require_once 'core/init.php';

// $user = DB::getInstanceDB()->get('users', array('username', '=','alex'));
// $user = DB::getInstanceDB()->makeQuery("SELECT * FROM users");
// $userInsert = DB::getInstanceDB()->insert('users', array(
//   'username'=> 'Dale',
//   'password'=> 'password',
//   'salt'=> 'salt'
// ));

$userUpdate = DB::getInstanceDB()-> update('users', 12, array(
  'password'=> 'newpassword',
  'name' => 'Ginger Timber'
));

// if ($userInsert){
//   echo 'Data inserted';
// }
