<?php

function __autoload($classname) {
    $filename =  '../../orm/'.$classname .".php";
    include_once($filename);
}

if (isset($_GET['id'])) {
    $GLOBALS['obj'] = ORM::getInstance();
    $GLOBALS['obj']->setTable('user');
    $id = $_GET['id'];
    $data = "image_name";
    $user = $GLOBALS['obj']->select($data, $id);
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
    $image = $user[0]['image_name'];
    $image = trim($image);
    $path = "$DOCUMENT_ROOT/project/uploads/user/$image"; //get path of user image
    $query = $GLOBALS['obj']->delete($id);
    //echo $path;
    unlink($path);          //delete user image		
    header("Location:users.php");
}

?>
