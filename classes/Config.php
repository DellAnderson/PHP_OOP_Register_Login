<?php
//this class is to make working wtih configuration easy in directory like style
//eg in index.php echo Congi::get('mysql/host'); // 127.0.0.1
//TODO decouple with init.php?
class config {
  public static function get($path = null){
    if($path){
      $config = $GLOBALS['config']; //$GLOBALS is from init.php
      $path = explode('/', $path); //explode path to array divide by character
      //print_r($path);
      //traverse our way trhu the array
      foreach($path as $element){
        //echo $element, ' '; //debug only
        if(isset($config[$element])){
          //echo 'Set',' '; //debug only
          $config = $config[$element];
        }
      }
      return $config; //TODO check for invalid path before return

    }
    return false; //if we don't have something in $path
  }
}
