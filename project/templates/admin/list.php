<?php 
      include'admin_header.php'; 
?>

 <link rel="stylesheet" type="text/css" href="../../css/users.css">

 <a href="addProduct.php" class="glyphicon glyphicon-plus"> Add Product</span></a>
 <table class="table table-striped user_table" >
            <tr style="text-align:center">
                <!---Header of the table-->
                <td><b>Name</td>
                <td><b>Price</td>
                <td><b>Image</td>
                <td><b>Action</td>
            </tr>

<?php 
	$obj = ORM::getInstance();
	$obj->setTable('product');
	$products = $obj->fill(array('id', 'name', 'price' ,'avaliable', 'image_name' ));  //select all products
	//var_dump($products[0]);
	if (gettype($products) == "array" ) {
	for ($i = 0 ; $i < count($products) ; $i++){ ?>

		<tr style="text-align:center">
      	  <?php
      		foreach ($products[$i] as $key=>$value){
      			//var_dump($key);
      			extract($products[$i]);
 			if ($key == "image_name") {
                                ?>	
                                <td><img src="../../uploads/product/<?php echo $value; ?>" width="40" height="40"/></td>
      			 <?php
                            }
                            if ($key != "image_name" and $key != "id" and $key != "avaliable") {
                                ?>
			
	  								<td><?php echo $value;?></td>
	  	
	  	<?php
                            }
                        }
                        ?>
                        <td><a href="edit1.php?id=<?php echo $id ?>">Edit</a><br><a href="delete.php?id=<?php echo $id ?>">Delete</a><br>                   
                   		 <a href="available.php?id=<?php echo $id ?>"> <?php echo $avaliable?> </a>                                     

                    </tr> 
                    <?php
                //var_dump($available);
            }} else {
                echo "No Products";
            }
//}
            ?>

        </table>


