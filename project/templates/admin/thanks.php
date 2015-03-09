<?php include 'admin_header.php'; 
//var_dump($_POST);

?>
<?php
//var_dump($_SESSION);
if(!$_SESSION){
	echo' <h1 class="warning"> OOPS! you should login first.....wait it will refresh automatic </h1>';
	header("Refresh:4; url=sign_in.php");
}else{
	$obj = ORM::getInstance();
	$obj->setTable('user');
	$col=array('id');
	$where=array(
		'name'=>$_POST['select']
		);
	$result = $obj->select($col,$where);
	//var_dump($result[0][0]);
	$user_id_1=$result[0][0];

//insert into order table
	$obj = ORM::getInstance();
	$obj->setTable('orders');
	$data=array(
		'user_id'=>$user_id_1,
		'notes'=>$_POST["notes_text"],
		'number_another_room'=>$_POST["room"],
		'date'=>date('Y-m-d H:i:s'),
		'status'=>'processing'
		);
	$result = $obj->insert($data);



//get order id
	$obj = ORM::getInstance();
	$obj->setTable('orders');
	$data=array('MAX(id)');
	$result = $obj->fill($data);
	
	$order=$result[0]['MAX(id)'];
	//var_dump($result[0]['MAX(id)']);

	$obj = ORM::getInstance();
	$obj->setTable('product');
	$data=array('name');
	$product = $obj->fill($data);

//var_dump($product);
	for($i=0;$i<count($product);$i++)
	{
			$products[]=$product[$i]["name"];

	}

	$all_products=array_values($products);
	$orders=array_keys($_POST);
	//var_dump($orders);
	//var_dump(count($orders));

	for($j=0;$j<count($orders);$j++){
		foreach($orders as $key=>$value){

			$orders[$key]=str_replace("_"," ",$value);

				}
		if(in_array($orders[$j],$all_products)){
			//var_dump($orders[$j]);

			$obj = ORM::getInstance();
			$obj->setTable('product');
			$data=array('id');
			$where=array(
				'name'=>$orders[$j]
				);

			$product = $obj->select($data,$where);

			foreach($orders as $key=>$value){

			$orders[$key]=str_replace(" ","_",$value);

				}

			//insert into order_product table
				$obj = ORM::getInstance();
				$obj->setTable('product_order');
				$data=array(
					'id'=>$order,
					'product_id'=>$product[0][0],
					'number_product'=>$_POST["$orders[$j]"]
				
					);
				$result = $obj->insert($data);

		}
	}

}
?>

<head>
<style>
h1{
margin-left: 88px;
font-size: 27px;
}
</style>
</head>


<?php header("Location:Admin.php"); ?>
