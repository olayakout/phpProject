<?php
if (isset($_GET['orderId'])) {

    function __autoload($classname) {
        $filename = '../../orm/' . $classname . ".php";
        include_once($filename);
    }
    ?>

    <table border="1" width="88%">
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
                <td><img src="../../uploads/product/<?php echo $productImage[$i];?>" width="40" height="40" ></img><?php echo "<br>".$productName[$i]; ?></td>
                <td><?php echo $productPrice[$i]; ?></td>
                <td><?php echo $productNum[$i]; ?></td>

            <?php }
            ?>

        </tr>
    </table>

    <?php
} else {
    ?>

    <?php include'admin_header.php'; ?>
    <style>
        #select{
            margin-left: 45%;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="../../css/users.css">
    <head><title>My Orders</title></head>
    <div class="welcome">
        <h1 class="brand-name">Checks</h1>
        <br>
        <form  method="post" >
            Date From
            <input type="datetime-local" name="from" value="2015-03-01T16:39:57" />
            Date To
            <input type="datetime-local" name="to" value="2015-03-30T16:39:57" />
            <input type="submit" name="submit" class="btn btn-default s" value="Go" /> 
        </form>
    </div>
    <br>

    <?php
    if (!empty($_SESSION['id']) and $_SESSION['is_admin'] == 1) {
        $_GLOBALS['obj'] = ORM::getInstance();
        $_GLOBALS['obj']->setTable('user');
        $users = $_GLOBALS['obj']->fill(array('id', 'name', 'is_admin'));  //select all users 


        if (gettype($users) == "array") {
            ?>
            <div class="container" >
                <select name="user" id="select">
                    <?php
                    for ($i = 0; $i < count($users); $i++) {
                        if ($users[$i]['is_admin'] == 0) {
                            ?>
                            <option value="<?php echo $users[$i]['name']; ?>"><?php echo $users[$i]['name']; ?></option>       
                            <?php
                        }
                    }
                    ?>
                </select>
            </div><br>
            <link rel="stylesheet" type="text/css" href="../../css/users.css">
            <table class="table table-striped user_table" >
                <tr style="text-align:center">
                    <!---Header of the table-->
                    <td><b>Name</td>
                    <td><b>Total Amount</td>
                </tr>
                <?php
                for ($i = 0; $i < count($users); $i++) {
                    if ($users[$i]['is_admin'] == 0) {
                        ?>
                        <tr style="text-align:center">

                            <td>
                                <?php echo $users[$i]['name']; ?>   <input type="submit" id="<?php echo "u" . $users[$i]['id']; ?>" value="+" class="btn defualt" data-toggle="button" onclick="showOrders('<?php echo implode(',', getOrders($users[$i]['id'])); ?>', this.id);" />

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
            <?php
        } else {
            echo "No Users";
        }
    } else {
        echo "Error";
    }
}

function getAmount($user_id) {
    $query = "select product_order.number_product ,product.price from product_order , product where product.id=product_order.product_id and product_order.id in (select id from orders where user_id =" . $user_id . ");";
    $productsWithPrice = $GLOBALS['obj']->query($query);
    if (gettype($productsWithPrice) == "array") {
        for ($i = 0; $i < count($productsWithPrice); $i++) {
            $price[] = $productsWithPrice[$i]['price'];
            $num[] = $productsWithPrice[$i]['number_product'];
        }

        $total = 0;
        for ($i = 0; $i < count($num); $i++) {
            $amount[] = $num[$i] * $price[$i];
            $total +=$amount[$i];
        }
        return $total;
    }
}

function getOrders($user_id) {
    $query = "SELECT product_order.number_product, product.price, orders.date , orders.id FROM product_order, product, orders WHERE product.id = product_order.product_id AND orders.id = product_order.id AND product_order.id IN (SELECT id FROM orders WHERE user_id = " . $user_id . ");";
    $results = $GLOBALS['obj']->query($query);
    if (gettype($results) == "array") {
        for ($i = 0; $i < count($results); $i++) {
            $price[] = $results[$i]['price'];
            $num[] = $results[$i]['number_product'];
            $amount[] = $num[$i] * $price[$i];
            $date[] = $results[$i]['date'];
            $id[] = $results[$i]['id'];
        }

        for ($i = 0; $i < count($date); $i++) {
            $orders[] = $date[$i];
            $orders[] = $amount[$i];
        }
        return array_merge($orders, $id);
    }
}
?>

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
            table.setAttribute("border", "1");
            table.setAttribute("class", "table table-striped user_table");
            var t = divOrder.appendChild(table);
            var header = document.createElement("tr");
            var h = t.appendChild(header);
            var col1 = document.createElement("th");
            col1.setAttribute("style","text-align:center");
            var c1 = h.appendChild(col1);
            c1.appendChild(document.createTextNode("Oder Date"));
            var col2 = document.createElement("th");
            col2.setAttribute("style","text-align:center");
            var c2 = h.appendChild(col2);
            c2.appendChild(document.createTextNode("Amount"));
            var count = 0;
            var startId = (len / 3) * 2;
            for (var i = 0; i < len / 3; i++) {              //# of rows
                var row = document.createElement("tr");
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
            console.log(values);
            console.log(id);
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
                    ajaxDisplay.innerHTML = ajaxRequest.responseText;
                }
            }
            ajaxRequest.open("GET", "checks.php?orderId=" + orderId, true);
            ajaxRequest.send(null);
        }
        else {
            btn.setAttribute("value", "+");
            document.getElementById("product").remove();
        }
    }

</script>