<?php
 include_once('../../orm/ORM.php');
if (isset($_GET['order_id'])) {
	 //var_dump($_GET['order_id']);
	 $GLOBALS['obj'] = ORM::getInstance();
	 $GLOBALS['obj']->setTable('orders');
	 $order_id = array( 'id'=>$_GET['order_id']);
    	 $data =array("status");
         $order = $GLOBALS['obj']->select($data, $order_id);
 	 //var_dump($order[0][0]);

      	 if ($order[0][0]=="Processing"){
		$order[0][0]="Out for delivery";
		
		
		}

	elseif($order[0][0]=="Out for delivery"){
		$order[0][0]="Done";
		
             }
	//echo $order[0][0];
	$mod_data=$GLOBALS['obj']->update(array('status'=>$order[0][0]), $order_id);
		

header("Location:Admin.php");
}
?>
