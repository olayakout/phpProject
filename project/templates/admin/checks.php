<body >
<?php
header("Refresh:60;url='checks.php'");
if (isset($_GET['orderId'])) {

    function __autoload($classname) {
        $filename = '../../orm/' . $classname . ".php";
        include_once($filename);
    }
    ?>

    <table border="1" width="88%" style="margin-left:79px">
        <tr style="text-align:center">
            <th style="text-align:center">
                Name
            </th>
            <th style="text-align:center">
                Price
            </th>
            <th style="text-align:center">
                Number
            </th>
        </tr>
        <?php
        $orderId = ltrim($_GET['orderId'], 'o');
        $_GLOBALS['obj'] = ORM::getInstance();
        $products = $_GLOBALS['obj']->query("SELECT product.name, product.image_name, product.price, product_order.number_product FROM product, product_order WHERE product.id = product_order.product_id AND product_order.id = " . $orderId . ";");

        $values = array_values($products);
        for ($i = 0; $i < count($values); $i++) {
            $val = array_values($values[$i]);
            $productName[] = $val[0];
            $productImage[] = $val[1];
            $productPrice[] = $val[2];
            $productNum[] = $val[3];
        }
        for ($i = 0; $i < count($productName); $i++) {
            ?>
            <tr style="text-align:center">
                <td><img src="../../uploads/product/<?php echo $productImage[$i]; ?>" width="40" height="40" ></img><?php echo "<br>" . $productName[$i]; ?></td>
                <td><?php echo $productPrice[$i]; ?></td>
                <td><?php echo $productNum[$i]; ?></td>

            <?php }
            ?>

        </tr>
    </table>
    <br>

    <?php
} else {
    ?>

    <?php include'admin_header.php';
    ?>
    <link rel="stylesheet" type="text/css" href="../../css/users.css">
    <head><title>Checks</title></head>
    <div class="welcome">
        <h1 class="brand-name" style="color: red; font-size:50px;" >Checks</h1>
    </div>
    <?php
    // var_dump($_SESSION['id']);
    // var_dump($_SESSION['is_admin'] );
    if ($_SESSION['is_admin'] == 1) {
        $_GLOBALS['obj'] = ORM::getInstance();
        $query = "select id,name from user where id in (select user_id from orders);";
        $users = $_GLOBALS['obj']->query($query);
        if (gettype($users) == "array") {
            ?>
            <div class="container" >
            </div>
            <link rel="stylesheet" type="text/css" href="../../css/users.css">
            <br>
            <br>

            <table class="table table-striped user_table" >
                <tr style="text-align:center">
                    <!---Header of the table-->
                    <td style="font-size: 20px;"><b >Name</td>
                    <td style="font-size: 20px;"><b>Total Amount</td>
                </tr>
                <?php
                for ($i = 0; $i < count($users); $i++) {
                    if ($users[$i]['is_admin'] == 0) {
                        ?>
                        <tr style="text-align:center">
                            <td>
                                <?php echo $users[$i]['name'];
                                ?>   <input type="submit" id="<?php echo "u" . $users[$i]['id']; ?>" value="+" class="btn defualt" data-toggle="button" onclick="showOrders('<?php echo implode(',', getOrders($users[$i]['id'])); ?>', this.id);" />

                            </td>
                            <td> <?php
                                echo getAmount($users[$i]['id']) . "<b>" . " EGP";
                                ?></td>
                        </tr>       
                        <?php
                    }
                }
                ?>
            </table>
            <div id="order" class="welcome" ></div>
            <div id="product" class="welcome" ></div>
            <?php } else {
            ?>
            <h1 class="brand-name">No Users</h1>
        <?php }
    } else {
        ?>
        <h1 class="brand-name">Error</h1>
    <?php }
}


function getAmount($user_id) {
$query = "select sum(product_order.number_product*product.price) as amount from product_order , product where product.id=product_order.product_id and product_order.id in (select id from orders where user_id =" . $user_id . ");";
$amount = $GLOBALS['obj']->query($query);
return $amount[0]['amount'];
}

function getOrders($user_id) {
$GLOBALS['obj']->setTable('orders');
$r = $GLOBALS['obj']->selectAll();
for ($i = 0; $i < count($r); $i++) {
if ($r[$i]['user_id'] == $user_id) {
$id[] = $r[$i]['id'];
$date[] = $r[$i]['date'];
}
}
for ($i = 0; $i < count($id); $i++) {
$query = "SELECT SUM( product_order.number_product * product.price ) as total FROM product_order, product WHERE product.id = product_order.product_id AND product_order.id = " . $id[$i] . ";";
$result = $GLOBALS['obj']->query($query);
$results[] = $result;
}
for ($i = 0; $i < count($results); $i++) {
$amount[] = $results[$i][0]['total'];
}

for ($i = 0; $i < count($date); $i++) {
$orders[] = $date[$i];
$orders[] = $amount[$i];
}
return array_merge($orders, $id);
}
?>
</body>
<script language="javascript" type="text/javascript">
    function showOrders(values, checked_id) {
        var id = checked_id;
        var btn = document.getElementById(id);
        var flag = $(btn).hasClass('active');
        if (flag == false) {
            values = values.split(",");
            var len = values.length;
            btn.setAttribute("value", "-");
            var divOrder = document.getElementById("order");
            var table = document.createElement("table");
            table.setAttribute("id", "to".concat(id));
            table.setAttribute("border", "1px groove");
            table.setAttribute("class", "table table-striped user_table");
            table.setAttribute("style", "margin-left:79px");
            var t = divOrder.appendChild(table);
            var header = document.createElement("tr");
            var h = t.appendChild(header);
            var col1 = document.createElement("th");
            col1.setAttribute("style", "text-align:center");
            var c1 = h.appendChild(col1);
            c1.appendChild(document.createTextNode("Order Date"));
            var col2 = document.createElement("th");
            col2.setAttribute("style", "text-align:center");
            var c2 = h.appendChild(col2);
            c2.appendChild(document.createTextNode("Amount"));
            var count = 0;
            var startId = (len / 3) * 2;
            for (var i = 0; i < len / 3; i++) {              //# of rows
                var row = document.createElement("tr");
                row.setAttribute("style", "text-align:center");
                var r = t.appendChild(row);
                for (var j = 0; j < 2; j++) {
                    var newcol = document.createElement("td");
                    var c = r.appendChild(newcol);   //table body tr td  
                    if (j == 1) {
                        var txt = document.createTextNode(values[count].concat(" EGP"));
                    }
                    else {
                        var txt = document.createTextNode("  ".concat(values[count]));
                        var input = document.createElement("input");
                        var variableToSend = values[startId];
                        input.setAttribute("type", "submit");
                        input.setAttribute("name", "submit");
                        input.setAttribute("id", "o".concat(values[startId]));
                        input.setAttribute("value", "+");
                        input.setAttribute("class", "btn defualt");
                        input.setAttribute("data-toggle", "button");
                        input.setAttribute("onclick", "showProducts(this.id);");
                        c.appendChild(input);
                        startId++;
                    }
                    c.appendChild(txt);
                    count++;
                }
            }

        } else {
            btn.setAttribute("value", "+");
            document.getElementById("to".concat(id)).remove();
            document.getElementById("product").remove();
        }
    }


    function showProducts(orderId) {
        var btn = document.getElementById(orderId);
        var flag = $(btn).hasClass('active');
        if (flag == false) {
            btn.setAttribute("value", "-");
            var ajaxRequest;
            try {
                ajaxRequest = new XMLHttpRequest();
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        alert("Your browser broke!");
                        return false;
                    }
                }
            }
            ajaxRequest.onreadystatechange = function () {
                if (ajaxRequest.readyState == 4) {
                    var ajaxDisplay = document.getElementById('product');
                    var div = document.createElement("div");
                    div.setAttribute("id", "d".concat(orderId));
                    ajaxDisplay.appendChild(div);
                    div.innerHTML = ajaxRequest.responseText;
                }
            }
            ajaxRequest.open("GET", "checks.php?orderId=" + orderId, true);
            ajaxRequest.send(null);
        }
        else {
            btn.setAttribute("value", "+");
            document.getElementById("d".concat(orderId)).remove();
        }
    }

</script>
