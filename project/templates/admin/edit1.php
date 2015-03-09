<?php
include'admin_header.php'; 



if (!empty($_GET['id'])) {
    $obj = ORM::getInstance();
    $obj->setTable('product');
    $id = array('id' => $_GET['id'] );
    //var_dump($id);
    //exit();
    $data[] = "*";
    $product = $obj->select($data, $id);
    //var_dump($product);
    //var_dump(extract($product));
    list($id, $name, $price,$available,$image_name,$category) = $product[0];

    $image = $image_name;
    //var_dump($image);
    $nameErr = $priceErr = $imageErr = " ";

    if ($_POST) {

        require '../../orm/validation.php';

        $rules = array(//Array of rules of fields
            'name' => 'required',
            'price' => 'required',
        );

        $validation = new Validation();
        $result = $validation->validate($_POST, $rules, $_FILES['image_name'],'product');

        if($result){
        if ($nameErr == " " and $priceErr == " "  and $imageErr == " ") { //Case of all feilds are valid
            if (strlen($_FILES['image_name']['tmp_name']) != 0) {
                $image = $_FILES['image_name']['name'];
            } else {
                $image = $image_name;
            }
            $id=array('id'=>$id);
            if( !in_array('Cancel', $_POST)){
            $result = $obj->update(array('name' => $_POST['name'], 'price' => $_POST['price'], 'image_name' => $image), $id);
            //var_dump($result);
            }
            header("Location:list.php");
        }}else{
            if( !in_array('Cancel', $_POST)){
            $nameErr = $priceErr = $imageErr = " ";
            foreach ($validation->errors as $error) {

                switch (explode(" ", $error)[0]) { //Switch for field name
                    case "name":
                        $nameErr = $error;
                        break;

                    case "price":
                        $priceErr = $error;
                        break;

                    case "ext":
                        $extErr = $error;
                        break;

                    case "image":
                        $imageErr = $error;
                        break;
                
            }
        }
        }else{
                        header("Location:list.php");

        }
    }
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
<td><input type="text" name="name" value="<?php if (!empty($name)) echo $name ?>" /><?php echo $nameErr; ?></td>
</tr>
<tr>
<td>Price</td>
<td><input type="number" name="price" min="5" step="0.5" value="<?php if (!empty($price)) echo $price; ?>" />
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
               
                <input type="submit" name="cancel" value="Cancel" class="btn btn-default s submit"/> 
 
            </div>

</form>



