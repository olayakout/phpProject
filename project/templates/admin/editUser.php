<?php
include'admin_header.php'; 
//var_dump($_SESSION);
if(!isset($_SESSION) || $_SESSION['is_admin']==0){
    if(!isset($_SESSION) ){
    echo' <h1 class="warning"> OOPS! you should login first.....wait it will refresh automatic </h1>';
    header("Refresh:4; url=../user/sign_in.php");
}
elseif($_SESSION['is_admin']==0){
     echo' <h1 class="warning"> you are not admin </h1>';
     header("Refresh:3; url=../user/Home.php");
}
}else{


if (!empty($_GET['id'])) {
    $obj = ORM::getInstance();
    $obj->setTable('user');
    $id = array('id' => $_GET['id'] );
    //var_dump($id);
    //exit();
    $data[] = "*";
    $user = $obj->select($data, $id);
    //var_dump($user);
    //var_dump(extract($user));
    list($id, $name, $email,$password,$image_name,$room,$ext,$login,$is_admin) = $user[0];

    $image = $image_name;
    //var_dump($image);
    $nameErr = $roomErr = $extErr = $imageErr = " ";

    if ($_POST) {

        require '../../orm/validation.php';

        $rules = array(//Array of rules of fields
            'name' => 'required',
            'room' => 'required',
            'ext' => 'required',
        );

        $validation = new Validation();
        $result = $validation->validate($_POST, $rules, $_FILES['image_name'],'user');

        if($result){
        if ($nameErr == " " and $roomErr == " " and $extErr == " " and $imageErr == " ") { //Case of all feilds are valid
            if (strlen($_FILES['image_name']['tmp_name']) != 0) {
                $image = $_FILES['image_name']['name'];
            } else {
                $image = $image_name;
            }
            $id=array('id'=>$id);
            if( !in_array('Cancel', $_POST)){
            $result = $obj->update(array('name' => $_POST['name'], 'room' => $_POST['room'], 'image_name' => $image, 'ext' => $_POST['ext']), $id);
            //var_dump($result);
            }
            header("Location:users.php");
        }}else{
            if( !in_array('Cancel', $_POST)){
            $nameErr = $roomErr = $extErr = $imageErr = " ";
            foreach ($validation->errors as $error) {

                switch (explode(" ", $error)[0]) { //Switch for field name
                    case "name":
                        $nameErr = $error;
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
        }
        }else{
                        header("Location:users.php");

        }
    }
}
}
?>


<head>
    <title>Edit User</title>     
</head>

<link rel="stylesheet" type="text/css" href="../../css/sign_in.css">

<div class="container_sign">
    <h1>Edit User</h1>
    <form  method="post" enctype="multipart/form-data">
        <table class="edit_table">
        <div class="form-group">
            <tr>
                <td>Name : </td>
                <td><input type="text" name="name" value="<?php if (!empty($name)) { echo $name;} ?>" /><?php echo $nameErr; ?></td>
            </tr>

            <tr>
            <td>Room No. :</td><td> <input type="text" name="room" value="<?php if (!empty($room)) {echo $room;} ?>" />
            <?php echo $roomErr; ?></td>
            </tr>

            <tr>
            <td>Ext. : </td>
            <td><input type="text" name="ext" 
            value="<?php if (!empty($ext)) {echo $ext;} ?>" /><?php echo $extErr; ?></td>
            </tr>

            <tr>
            <td>Profile Picture : </td>
            <td><input type="file" name="image_name" /><?php echo $imageErr; ?></td>
            </tr>
            </table>
            
            <div>
            <input type="submit" name="submit" value="submit" class="btn btn-default s submit" /> 
           
            <input type="submit" name="cancel" value="Cancel" class="btn btn-default s submit"/> 
            </div>
    </form>
<?php
}?>