<?php
session_start();
function __autoload($classname) {
    $filename =  '../../orm/'.$classname .".php";
    include_once($filename);
}
$data= array('login' => 0);
          $where= array(
              'name' => $_SESSION['name']
            );

          $obj = ORM::getInstance();
          $obj->setTable('user');

          $array = $obj->update($data,$where);
session_destroy();
header("Location:Home.php");
?>
