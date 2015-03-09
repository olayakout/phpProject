<?php 
 include'admin_header.php'; 
 //var_dump($_SESSION);
 $name=$_SESSION['name'];
 $is_admin=$_SESSION['is_admin'];
$nameErr = $emailErr = $passwordErr = $confirmPasswordErr = $roomErr = $extErr = $imageErr = " "; //Initialize errors
if ($_POST) {
    //var_dump($_POST);
    //var_dump($_SESSION);
    require '../../orm/validation.php';

    $rules = array(//Array of rules of fields
        'name' => 'required',
        'email' => 'email%required',
        'password' => 'required%length',
        'confirmPassword' => 'required%samePwd',
        'room' => 'required',
        'ext' => 'required',
    );

    $validation = new Validation();       //call to vaidation 
    $result = $validation->validate($_POST, $rules, $_FILES['image_name'],'user');
    $nameValue=$_SESSION['name']=$_POST['name'];
    $emailValue=$_SESSION['email']=$_POST['email'];
    $roomValue=$_SESSION['room']=$_POST['room'];
    $extValue=$_SESSION['ext']=$_POST['ext'];



    //var_dump($result);

    if ($result == TRUE) { //Case of all feilds are valid
        $nameErr = $emailErr = $passwordErr = $confirmPasswordErr = $roomErr = $extErr = $imageErr = " "; // No errors
        $obj = ORM::getInstance();
        $obj->setTable('user');
        // insert new user 
        if(!empty($_FILES['image_name']['name']))
        $result = $obj->insert(array('name' => $_POST['name'], 'email' => $_POST['email'], 'password' => md5($_POST['password']), 'room' => $_POST['room'], 'image_name' => $_FILES['image_name']['name'], 'ext' => $_POST['ext']));
        if(empty($_FILES['image_name']['name']))
        $result = $obj->insert(array('name' => $_POST['name'], 'email' => $_POST['email'], 'password' => md5($_POST['password']), 'room' => $_POST['room'], 'ext' => $_POST['ext']));

        if($result)
            header("Location:users.php");
    }
    if (!empty($_POST) or $result == FALSE) { //Case of one or more feilds are invalid
        $nameErr = $emailErr = $passwordErr = $confirmPasswordErr = $roomErr = $extErr = $imageErr = " ";
        foreach ($validation->errors as $error) {
            switch (explode(" ", $error)[0]) { //Switch for field name
                case "name":
                    $nameErr = $error;
                    break;

                case "email":
                    $emailErr = $error;
                    break;

                case "password":
                    $passwordErr = $error;
                    break;

                case "confirmPassword":
                    $confirmPasswordErr = $error;
                    break;

                case "room":
                    $roomErr = $error;
                    break;

                case "ext":
                    $extErr = $error;
                    break;

                case "image":
                    $imageErr = $error;
                    break;
            }
        }}else{
                        header("Location:users.php");

            }
        
    
}
?>

    <head>
        <title>Add User</title>	 
    </head>
    
    <link rel="stylesheet" type="text/css" href="../../css/addUser.css">

<div class="container_sign">
    <h1>Add User</h1>
    <form  method="post" enctype="multipart/form-data">
        <table class="edit_table">
        <div class="form-group">
               <tr>
               <td> Name : </td>
               <td><input type="text" name="name" value="<?php
                if (!empty($nameValue)) {
                    echo $nameValue;
                }
                ?>" /><?php echo $nameErr; ?></td>
                </tr>
                <tr>

                <td>Email : </td>
                <td><input type="text" name="email" value="<?php
                if (!empty($emailValue)) {
                    echo $emailValue;
                }
                ?>" /><?php echo $emailErr; ?></td>
                </tr>
                <tr>
                <td>Password :</td>
                <td> <input type="password" name="password"/><?php echo $passwordErr; ?></td>
                </tr>
                <tr>                          
                <td>Confirm Password :</td>
                <td> <input type="password" name="confirmPassword" /><?php echo $confirmPasswordErr; ?></td>
                </tr>

                <tr>

                <td>Room No. : </td>
                <td><input type="text" name="room" value="<?php
                if (!empty($roomValue)) {
                    echo $roomValue;
                }
                ?>" /><?php echo $roomErr; ?></td>
                </tr>

                <tr>

                <td>Ext. : </td>
                <td><input type="text" name="ext" value="<?php
                if (!empty($extValue)) {
                    echo $extValue;
                }
                ?>" /><?php echo $extErr; ?></td>
                </tr>
                <tr>
                <td>Profile Picture : </td>
                <td><input type="file" name="image_name"/><?php echo $imageErr; ?></td></tr>
                </div>
                </table>
            <div>
                <input type="submit" name="submit" value="submit" class="btn btn-default s submit" /> 
               
                <input type="reset" name="rest" value="Reset" class="btn btn-default s submit" onclick="<?php session_unset();  if(isset($_POST)){$_POST = array();}  $_SESSION['name']=$name; $_SESSION['is_admin']=$is_admin;  $_POST = array(); ?>" /> 
            </div>
           
        </form>


</div>
