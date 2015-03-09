    <link rel="stylesheet" type="text/css" href="../../css/Admin.css">
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
     header("Refresh:2; url=../user/Home.php");
}
}else{

?>

<?php
mysql_connect("localhost","root","ola") or die(mysql_error());
mysql_select_db("Cafeteria");
$result=mysql_query("SELECT distinct u.name,u.room ,u.ext ,o.date ,o.status ,o.id  FROM product d,product_order p ,user u ,orders o WHERE d.id=p.product_id and o.user_id=u.id and o.id=p.id and o.status!='Done' order by o.date Desc ");
//var_dump($result);
?>

<?php
while($row=mysql_fetch_array($result)){
?>


 <table class="table-striped main" >
            <tr style="text-align:center">
                <!---Header of the table-->
                <td><b>User Name</td>
                <td><b>Room</td>
                <td><b>Ext.</td>
                <td><b>Order Date</td>
                <td><b>Action</td>
            </tr>
<tr style="text-align:center">
        <?php for($i=0;$i<=3;$i++) { ?>
            <td><p><?php echo $row[$i]; ?></p></td>
          <?php } ?>  

            <td><a href="deliver.php?order_id=<?php echo $row[5] ?>"><?php echo $row[4]; ?></p></td>
</tr>

        <?php
        $sql=mysql_query("SELECT distinct d.name,d.price,d.image_name, p.number_product FROM product d,product_order p ,orders o WHERE d.id=p.product_id and p.id=".$row[5]."");

        ?>
</table>
<table class="table-striped inside">
<tr style="text-align: center; background: none repeat scroll 0% 0%  rgba(215, 235, 240, 0.54)">
<?php
while($row1=mysql_fetch_array($sql)){
    //var_dump($row1[2]);
?>



<td class="inside_td"><img src="<?php echo "../../uploads/product/$row1[2]";?>" width="40" height="40"/><br />
<?php echo $row1[0]; ?><br />
<?php echo $row1[1]; ?> <br />
<?php echo $row1[3]; ?> <br />


 <?php
 //var_dump($row1[3]);
}?></td>
</tr>
</table>
<?php
$sql1=mysql_query("SELECT sum(price*number_product) s FROM product d,product_order p WHERE d.id=p.product_id and p.id=".$row[5]."");
$row2=mysql_fetch_array($sql1);
?>
<table class="table-striped out">
<tr style="text-align:center" class="table-striped">
<td><?php echo "total=".$row2["s"]; ?></td></tr>
    <?php
}
header("Refresh:60;url='Admin.php'");
?>
 </table>
<?php
}
?>