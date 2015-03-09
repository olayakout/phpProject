
<?php 
 include'admin_header.php'; 

if (!empty($_GET['id'])) {
    $obj = ORM::getInstance();
    $obj->setTable('product');
    $id = $_GET['id'];
    $data = "*";
    $product = $obj->selectwhere($data, $id);

    extract($product[0]);
    $image = $image_name;
    $nameErr = $priceErr = $imageErr = " ";

if ($_POST) {
        require 'validation.php';
        $rules = array(//Array of rules of fields
            'name' => 'required',
            'price' => 'required',
        );

       $validation = new Validation();       //call to vaidation 
       $result = $validation->validate($_POST,$rules, $_FILES['image_name'],);
	
	if ($nameErr == " " and $priceErr == " " and $imageErr == " ") { //Case of all feilds are valid
            if (strlen($_FILES['image_name']['tmp_name']) != 0) {
                $image = $_FILES['image_name']['name'];
            } else {
                $image = $image_name;
}
	$result = $obj->update(array('name'=>$_POST['name'],'price'=>$_POST['price'],'category'=> $_POST['category'], 		'image_name'=> $image),$id);

            header("Location:/cafeteria/product/list.php");
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
	}
      }
   }
}
?>

        <title>Edit product</title>	 

<form method="post" enctype="multipart/form-data">
Product<input type="text" name="name" value="<?php if (!empty($name)) echo $name ?>" /><?php echo $nameErr; ?><br>
<br /> 
Price<input type="number" name="price" min="5" step="0.5" value="<?php if (!empty($price)) echo $price; ?>" />
<label>EGP</label><br />
<?php echo $priceErr; ?><br>
<br />

<?php
$cn=mysql_connect('localhost','root','mona') or die(mysql_error());
mysql_select_db('cafeteria',$cn) or die(mysql_error());
$sql = "SELECT name FROM category";
$rs = mysql_query($sql) or die(mysql_error());
echo "Category <select name='category'>";
while($row = mysql_fetch_array($rs)){
echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
}mysql_free_result($rs);
echo "</select>";

?>
<a href="addCategory.php">add Category</a><br />
<br />
<label for=""image_name"">Product Image</label>
<input type="file" name="image_name" /><br /><?php echo $imageErr; ?><br>
<br />
<input type="submit" name="submit" value="Save" />
<input type="reset" name="reset" value="reset" /> 
</form>


