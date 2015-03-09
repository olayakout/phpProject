<?php
 include_once('../../orm/ORM.php');

if (isset($_GET['id'])) {
	 //var_dump($_GET['id']);
	 $GLOBALS['obj'] = ORM::getInstance();
	 $GLOBALS['obj']->setTable('product');
	 $id = array( 'id'=>$_GET['id']);
    	 $data =array("avaliable");
         $product = $GLOBALS['obj']->select($data, $id);
         //var_dump($product[0][0]);
         //exit();
      	 if ($product[0][0]=="avaliable"){
		$product[0][0]="unavaliable";
		//echo $product[0]["avaliable"]."<br />";
		}
	else{
		$product[0][0]="avaliable";
		//echo $product[0]["avaliable"]."<br />";
             }
	$data1=array("name,price,image_name,category,date");
	$query=$GLOBALS['obj']->select($data1, $id);
	var_dump($query);
	//exit();
	$mod_data=$GLOBALS['obj']->update(array('avaliable'=>$product[0][0]),$id);
		

 header("Location:list.php");
}
?>

