<?php
session_start();
error_reporting(0);

function __autoload($classname) {
    $filename =  '../../orm/'.$classname .".php";
    include_once($filename);
}
//var_dump($_SESSION);
$session = array_filter($_SESSION);
//var_dump($session);
if(isset($_SESSION)&&!empty($session)){
$username=$_SESSION['name'];

$colums=array('image_name','login','is_admin');

$where= array(
	'name' => $username 
	);

$obj = ORM::getInstance();
	$obj->setTable('user');
	$array = $obj->select($colums,$where);
	//var_dump($array);
	if($array){
	$var=array('image'=>$array[0][0],'log'=>$array[0][1],'is_admin'=>$array[0][2]);
	extract($var);
	//var_dump($image);
	//var_dump($log);
	}
	else{$log=0;}
}	
else{$log=0;}

if($is_admin==0){
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	

	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../../css/rtl.css">	 
	<link rel="stylesheet" type="text/css" href="../../css/style.css">

	<!--[if lt IE 9]-->
	<script src="../../js/html5shiv.min.js"></script>
	<script src="../../js/respond.min.js"></script>


</head>

<body>
	<div class="container">
	<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<nav class="navbar navbar-default ">

		  

		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->

		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" >MNO Cafe</a>
		    </div>


		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav" >
		       <li><a href="Home.php">Home</a></li>
		      	<li><a href="location.php">About</a></li>
		        <li <?php if(!$log||$is_admin==1){ echo 'style="display: none;';}?>><a href="Menu.php">Menu <span class="sr-only">(current)</span></a></li>
		        <li <?php if(!$log ||$is_admin==1){ echo 'style="display: none;';}?>> <a href="MyOrders.php">My Orders</a></li>
		        <li <?php if($log && $is_admin==0 ){ echo 'style="display: none;';}?> ><a href="sign_in.php">Sign in</a></li>

		      </ul>
		    
		      <ul class="nav navbar-nav navbar-right" <?php if(!$log||$is_admin==1){ echo 'style="display: none;';}?>>
		        <li><img class="image" src="<?php echo "../../uploads/user/$image";?>"/></li>

		        <li><a href=""><?php echo $username ; ?> </a></li>

		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <span class="caret"></span></a>

		        <ul class="dropdown-menu" role="menu">
		            <li><a href="logout.php">Log out</a></li>
		          </ul>

		        </li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
</nav>

	</div>
	</div>
	</div>
	<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
</body>
</html>

<?php }else{
	header("Location:error.php");
}
?>