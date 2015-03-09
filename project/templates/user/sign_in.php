
<?php 
      include'header.php'; 
?>

<link rel="stylesheet" type="text/css" href="../../css/sign_in.css">

<div class="container_sign">
  <h1>Sign In</h1>
  <form role="form" method="post">
  <table>
    <div class="form-group">
     <tr>
     <td> <label for="email">Email:</label></td>
      <td><input type="email" class="form-control" id="email" placeholder="Enter email" name="email"></td>
     </tr> 
    </div>
    <div class="form-group">
    <tr>
      <td><label for="pwd">Password:</label></td>
      <td><input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password"></td>
    </tr>  
    </div>
    
    </table>
    <button type="submit" class="btn btn-default s" >Submit</button>
  </form>

  <div class="error">
<?php
  if($_POST)
  {
    require '../../orm/validation.php';
    $rules=array(
      'email'=>'email%required',
      'password'=>'required', 
    );
    
  $validation=new Validation();
  if($validation->validate($_POST,$rules)==TRUE)
  {
      $colums=array('id','email','password','name','is_admin');

      $where= array(
        'email' => $_POST['email'],
        'password'=>md5($_POST['password'])
        );

      $obj = ORM::getInstance();
      $obj->setTable('user');
      $array = $obj->select($colums,$where);
      //var_dump($array);
      if($array&&isset($_SESSION)){
      $_SESSION['id']=$array[0][0];  
      $_SESSION['email']=$array[0][1];
      $_SESSION['name']=$array[0][3];
      $is_admin=$_SESSION['is_admin']=$array[0][4];


      //var_dump($_SESSION['name']);
    }
      if($array){
          //echo'in side';

          $data= array('login' => 1);
          $where= array(
              'email' => $_POST['email']
            );

          $obj = ORM::getInstance();
          $obj->setTable('user');

          $array = $obj->update($data,$where);
           //var_dump($array);
          if($array){
            if($is_admin!=1){
             header("Location:Home.php");
            }else{
             header("Location:../admin/Admin.php");

            }
            }


      }else{
        echo 'Please,Check your Email and Password';
      }

                                 
  }
      else
      {                                
    foreach($validation->errors as $error)
                {
      echo $error;

    }
  }  

  }

?>
</div>
</div>

