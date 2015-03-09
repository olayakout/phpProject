<?php 
 include'admin_header.php'; 
 //var_dump($_SESSION);
 $name=$_SESSION['name'];
 $is_admin=$_SESSION['is_admin'];

$nameErr = $priceErr = $imageErr = " "; //Initialize errors
if ($_POST) {
    require '../../orm/validation.php';
    $rules = array(//Array of rules of fields
        'name' => 'required',
      	'price' => 'required',
          );

    $validation = new Validation();       //call to vaidation 
    $result = $validation->validate($_POST,$rules, $_FILES['image_name'],'product');
	
 if ($result == TRUE) { //Case of all feilds are valid
	$nameErr = $priceErr = $imageErr = " "; //Initialize errors
 	$obj = ORM::getInstance();
        $obj->setTable('product');
        // insert new product 
        $date = date('Y-m-d H:i:s');
        $result = $obj->insert(array('name' => $_POST['name'],'price'=>$_POST['price'], 'category'=>$_POST['category'] , 
                'image_name'=>$_FILES['image_name']['name'],'date' => $date)); 
        //var_dump($result);
        if($result)
	           header("Location:list.php");
      		        
		}
 if (!empty($_POST) or $result == FALSE) { //Case of one or more feilds are invalid
        $nameErr = $priceErr = $imageErr = " ";
        foreach ($validation->errors as $error) {
            switch (explode(" ", $error)[0]) 
		{ //Switch for field name
                case "name":
                    $nameErr = $error;
                    break;

                case "price":
                    $priceErr = $error;
                    break;

                case "image":
                    $imageErr = $error;
                    break;
              }
        }}else{
                            header("Location:list.php");

            }
        
    
}
?>

 <link rel="stylesheet" type="text/css" href="../../css/addProduct.css">

<div class="container_sign">
    <h1>Add Product</h1>
    <form  method="post" enctype="multipart/form-data">
        <table class="edit_table">
        <div class="form-group">

<tr>
<td>Product</td>
<td><input type="text" name="name" value="<?php if (!empty($nameValue)) echo $nameValue ?>" /><?php echo $nameErr; ?></td>
</tr>
<tr>
<td>Price</td>
<td><input type="number" name="price" min="5" step="1" value="<?php if (!empty($priceValue)) echo $priceValue; ?>" />
<label>EGP</label>

<?php echo $priceErr; ?></td>
</tr>

<?php

$obj = ORM::getInstance();
$obj->setTable('category');
$names = $obj->fill(array('name'));  
//var_dump($names[0]["name"]);

echo"<tr>";

echo "<td> Category </td> <td><select name='category'>";
for($i=0;$i<count($names);$i++){
  
echo "<option value='".$names[$i]["name"]."'>".$names[$i]["name"]."</option>";
}
echo "</select>";

?>
<a href="addCategory.php">add Category</a></td>
</tr>
<tr>
<td><label for="image_name">Product Image</label></td>
<td><input type="file" name="image_name" /><?php echo $imageErr; ?></td>
</tr>
</table>
<div>
                <input type="submit" name="submit" value="Save" class="btn btn-default s submit" /> 
               
                <input type="reset" name="rest" value="reset" class="btn btn-default s submit" onclick="<?php session_unset();  if(isset($_POST)){$_POST = array();}  $_SESSION['name']=$name; $_SESSION['is_admin']=$is_admin;  $_POST = array(); ?>" /> 
            </div>

</form>



