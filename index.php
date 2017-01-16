<?php
require_once 'core/init.php';

//echo phpversion();
//echo Config::get('mysql/host'); //expect 127.0.0.1
//$db = new DB(); //won't work for singleton pattern

DB::getInstanceDB(); //create a single database connection
