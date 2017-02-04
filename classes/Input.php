<?php class Input {
  public static function exists($type = 'post'){
    switch($type){
      case 'post':
        return (!empty($_POST)) ? true : false;
      break;
      case 'get':
        retunr(!empty($_GET)) ? true : false;
      break;
      default:
        return false;
      break;
    }
  }

  public static function get($item){
    if(isset($_POST[$item])){
      return $_POST[$item];
    } else if (isset($_GET[$item])){
      return $_GET[$item];
    }
    return'';  //if don't return something, will get error, so empty string it is!
  }
} 
