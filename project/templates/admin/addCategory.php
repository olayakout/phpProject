<?php
 include'admin_header.php'; 

$categoryErr = " "; 
if ($_POST) {
    require '../../orm/validation.php';
    $rules = array(//Array of rules of fields
        'category' => 'required',
    );
 $validation = new Validation();       //call to vaidation 
    $result = $validation->validate($_POST, $_FILES, $rules);
	
 if ($result == TRUE) { //Case of all feilds are valid
	$categoryErr = " "; 
 	$obj = ORM::getInstance();
        $obj->setTable('category');
        // insert new category
	 $result = $obj->insert(array('name' => $_POST['category']));

header("Location:addProduct.php"); 
}
if (!empty($_POST) or $result == FALSE) { 
      	$categoryErr = " "; 
        foreach ($validation->errors as $error) {
            switch (explode(" ", $error)[0]) 
		{ //Switch for field name
                case "category":
                    $categoryErr = $error;
                    break;
		}
	}
}
}
?>
<label><h1>Add Category</h1></label>

<form method="post" >
New Category<input type="text" name="category" /><?php echo $categoryErr; ?><br>
<br /> 

<input type="submit" name="submit" value="Save" />
<input type="reset" name="reset" value="reset" /> 
</form>
</html>
