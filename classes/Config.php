<?php
//this class is to make working wtih configuration easy in directory like style
//eg in index.php echo Congi::get('mysql/host'); // 127.0.0.1
//TODO decouple with init.php?
class config {
  public static function get($path = null){
    if($path){
      $path = explode('/', $path); //explode path to array divide by character
      $config = $GLOBALS['config']; //$GLOBALS is from init.php
      //print_r($path);
      //traverse our way trhu the array
      foreach($path as $element){
        //echo $element, ' '; //debug only
        if(isset($config[$element])){
          //echo 'Set',' '; //debug only
          $config = $config[$element];
        } elseif(!isset($config[$element])){ //my code: may return false instead of string
          //return 'Error: invalid configuration parameter path.';
          return false;
        }
      }

      return $config; //TODO check for invalid path before return

    }
    return false; //if we don't have something in $path
  }
}
