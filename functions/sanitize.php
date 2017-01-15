<?php
//sanitize going in and escape when coming out
function escape($string){
  //http://php.net/manual/en/function.htmlentities.php
  //convert all appplicable characters in string passsed in to htmlentities
  //& escape single & double quotes & define character encoding
  return htmlentities($string, ENT_QUOTES, 'UTF-8');

}
