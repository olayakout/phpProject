

<?php 
      include'admin_header.php'; 
?>

 <link rel="stylesheet" type="text/css" href="../../css/users.css">

 <a href="addUser.php" class="glyphicon glyphicon-plus"> Add Users</span></a>
 <table class="table table-striped user_table" >
            <tr style="text-align:center">
                <!---Header of the table-->
                <td><b>Name</td>
                <td><b>Room</td>
                <td><b>Image</td>
                <td><b>Ext</td>
                <td><b>Action</td>
            </tr>
            <?php

            $obj = ORM::getInstance();
            $obj->setTable('user');
            $users = $obj->fill(array('id', 'name', 'room', 'image_name', 'ext','is_admin'));  //select all users 
            //var_dump($users[0]);
            if (gettype($users) == "array" ) {
                //var_dump($users[0]["is_admin"]);
                for ($i = 0; $i < count($users); $i++) {
                    if($users[$i]["is_admin"]!=1){
                    ?>

                    <tr style="text-align:center">

                        <?php
                        foreach ($users[$i] as $key => $value) {

                            extract($users[$i]);
                            if ($key == "image_name") {
                                ?>	
                                <td><img src="../../uploads/user/<?php echo $value; ?>" width="40" height="40"/></td>	
                                <?php
                            }
                            if ($key != "image_name" and $key != "id" and $key!="is_admin") {
                                ?>
                                <td><?php echo $value; ?></td>
                                <?php
                            }
                        }
                        ?>
                        <td><a href="editUser.php?id=<?php echo $id ?>">Edit</a><br><a href="deleteUser.php?id=<?php echo $id ?>">Delete</a></td>                    
                    </tr> 
                    <?php
                }
            }} else {
                echo "No Users";
            }
//}
            ?>

        </table>





