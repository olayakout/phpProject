<?php

function __autoload($classname) {
    $filename =  '../../orm/'.$classname .".php";
    include_once($filename);
}

if (isset($_GET['id'])) {
    $GLOBALS['obj'] = ORM::getInstance();
    $GLOBALS['obj']->setTable('product');
    $id = $_GET['id'];
    $data = "image_name";
    $product = $GLOBALS['obj']->select($data, $id);
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
    $image = $product[0]['image_name'];
    $image = trim($image);
    $path = "$DOCUMENT_ROOT/project/uploads/product/$image"; //get path of product image
    $query = $GLOBALS['obj']->delete($id);
    //echo $path;
    unlink($path);          //delete product image     
    header("Location:list.php");
}

?>
