<?php include 'header.php'; ?>
<link rel="stylesheet" type="text/css" href="../../css/Menu.css">

<?php
//var_dump($_SESSION);
if(!$_SESSION){
	echo' <h1 class="warning"> OOPS! you should login first.....wait it will refresh automatic </h1>';
	header("Refresh:4; url=sign_in.php");
}else{
	$obj = ORM::getInstance();
	$obj->setTable('user');
	$data=array('room');
	$where=array(
		'id'=>$_SESSION["id"]
		);

	$room = $obj->select($data,$where);
	$room=$room[0][0];
	//var_dump($room[0][0]);
?>

<form action="thanks.php"  method="post" class="selectBox" id="box">
<input name="username" value="<?php echo $_SESSION['name'];?>" hidden >
<h1>Current Order</h1>
<p id="welcome" class="welcome"> Please , press on products to choose your order... </p>
<table id="order" class="order_table"></table>

</form>
<table >
<!--<div class="products">-->
<?php
	$obj = ORM::getInstance();
	$obj->setTable('product');
	$products = $obj->order();
	//var_dump($products);
	

?>
<tr>
<?php	
	for ($i = 0 ; $i < count($products) ; $i++){
		$image="../../uploads/product/";
		$image.=$products[$i]['image_name'];
		$day=time()-strtotime($products[$i]['date']);
		$day=$day/(60*60*24);
		//var_dump($day);
		$name=$products[$i]['name']; 
		$price=$products[$i]['price'];

		$array_parameters = array($name,$price,$room);
		//var_dump($array_parameters);
        $parameters = implode(",",$array_parameters);
        //var_dump($parameters);

?>
			

			<td class="product_td"><div class="new" <?php if($products[$i]['avaliable']=='unavaliable'){echo'style="opacity:0.5"';} ?>> <?php if($day<3){ echo'NEW';}?></div>
			
			<button type="button" class="button" <?php if($products[$i]['avaliable']=='unavaliable'){echo'disabled';} ?> 
					onClick="info('<?php echo $parameters; ?>')">
				<img 
				src="<?php  echo $image; ?>" <?php if($products[$i]['avaliable']=='unavaliable'){echo'style="opacity:0.5"';} ?>
				class="image_procedure"
				/>
			</button></td>

			<td class="product_td"><label  class="label_product" <?php if($products[$i]['avaliable']=='unavaliable'){echo'style="opacity:0.5"';} ?>><?php  echo $products[$i]['name'];?> </label>
			<br /> <div class="price" <?php if($products[$i]['avaliable']=='unavaliable'){echo'style="opacity:0.5"';} ?>> 	<?php  echo $products[$i]['price']." L.E ";?> </div>
			</td>
	
<?php			
	}
?>
</tr>
<!--</div>-->
</table>	
<?php
}
?>

	<script type="text/javascript" src="../../js/Menu.js"></script>
