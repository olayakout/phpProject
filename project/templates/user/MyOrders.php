<?php include 'header.php'; 
header("Refresh:60;url='MyOrders.php'");
?>
<link rel="stylesheet" type="text/css" href="../../css/users.css">
<head><title>My Orders</title></head>
<div class="welcome">
    <h1 class="brand-name">My Orders</h1>
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
<table class="table table-striped user_table" >
    <tr style="text-align:center">
        <!---Header of the table-->
        <td><b>Oder Date</td>
        <td><b>Status</td>
        <td><b>Amount</td>
        <td><b>Action</td>
    </tr>

    <?php
    if (!empty($_SESSION['id']) and $_SESSION['is_admin'] == 0) {
        $GLOBALS['obj'] = ORM::getInstance();

        if (empty($_POST)) {
            $query = "select id , date , status from orders where user_id = " . $_SESSION['id'] . " order by  date desc limit 5;";
            $orders = $GLOBALS['obj']->query($query);
            if (gettype($orders) == "array") {
                for ($i = 0; $i < count($orders); $i++) {
                    ?>
                    <tr style="text-align:center">
                        <?php
                        foreach ($orders[$i] as $key => $value) {
                            if ($key == "date") {
                                ?>

                                <td><?php echo $value; ?>   <input type="submit" id="<?php echo $orders[$i]['id']; ?>" value="+" class="btn defualt" data-toggle="button" onclick="showProducts('<?php echo implode(',', getProducts($orders[$i]['id'])); ?>', this.id);" />

                                </td>
                                <?php
                            }
                            if ($key == "status") {
                                ?>
                                <td><?php echo $value; ?></td>
                                <?php
                            }
                        }
                        if ($orders[$i]['status'] == "Processing") {
                            ?>
                            <td><?php
                                $amount[] = getAmount($orders[$i]['id']);
                                echo $amount[$i] . " EGP";
                                ?></td>
                            <td><a href="cancelOrder.php?id=<?php echo $orders[$i]['id'] ?>">Cancel</a></td>
                            <?php } else { ?>
                            <td><?php
                                $amount[] = getAmount($orders[$i]['id']);
                                echo $amount[$i] . " EGP";
                                ?></td>
                            <td></td>
                    <?php } ?>
                    </tr> 

            <?php }
            ?>
            </table>
            <div id="totl" class="welcome"><?php
                $total = 0;
                for ($i = 0; $i < count($amount); $i++) {
                    $total +=$amount[$i];
                }echo "<b>" . "Total : " . $total . " EGP";
                ?></div><br>
            <div id="product" class="welcome" ></div>

        <?php } else {
            ?>
            <h1 class="brand-name">No Results</h1>
            <?php
        }
    } else {
        $from = $_POST['from'];
        $to = $_POST['to'];
        $from_date = date('Y-m-d H:i:s', strtotime($from));
        $to_date = date('Y-m-d H:i:s', strtotime($to));
        $query = "select id , date , status from orders where user_id = " . $_SESSION['id'] . " and date between '" . $from_date . "' and '" . $to_date . "' ;";
        $orders = $GLOBALS['obj']->query($query);
        if (gettype($orders) == "array") {
            for ($i = 0; $i < count($orders); $i++) {
                ?>
                <tr style="text-align:center">
                    <?php
                    foreach ($orders[$i] as $key => $value) {
                        if ($key == "date") {
                            ?>

                            <td><?php echo $value; ?>   <input type="submit" id="<?php echo $orders[$i]['id']; ?>" value="+" class="btn defualt" data-toggle="button" onclick="showProducts('<?php echo implode(',', getProducts($orders[$i]['id'])); ?>', this.id);" />

                            </td>
                            <?php
                        }
                        if ($key == "status") {
                            ?>
                            <td><?php echo $value; ?></td>
                            <?php
                        }
                    }
                    if ($orders[$i]['status'] == "Processing") {
                        ?>
                        <td><?php
                    $amount[] = getAmount($orders[$i]['id']);
                    echo $amount[$i] . " EGP";
                    ?></td>
                        <td><a href="cancelOrder.php?id=<?php echo $orders[$i]['id'] ?>">Cancel</a></td>
                        <?php } else { ?>
                        <td><?php
                        $amount[] = getAmount($orders[$i]['id']);
                        echo $amount[$i] . " EGP";
                        ?></td>
                        <td></td>
                <?php } ?>
                </tr> 

                <?php }
                ?>
            </table>
            <div id="total" class="welcome"><?php
                $total = 0;
                for ($i = 0; $i < count($amount); $i++) {
                    $total +=$amount[$i];
                }echo "<b>" . "Total : " . $total . " EGP";
                ?></div><br>
            <div id="product" class="welcome" ></div>
        <?php } else {
            ?>
            <h1 class="brand-name">No Results</h1>
            <?php
        }
    }
} else {
    ?>
    <h1 class="brand-name">Error</h1> 
<?php
}

function getAmount($order_id) {
    $query = "SELECT sum(product_order.number_product * product.price) as amount FROM product_order, product WHERE product.id = product_order.product_id AND product_order.id = " . $order_id . ";";
    $amount = $GLOBALS['obj']->query($query);
    return $amount[0]['amount'];
}

function getProducts($order_id) {
    $query = "SELECT product.name, product.image_name, product.price, product_order.number_product FROM product, product_order WHERE product.id = product_order.product_id AND product_order.id = " . $order_id . ";";
    $products = $GLOBALS['obj']->query($query);
    $values = array_values($products);
    for ($i = 0; $i < count($values); $i++) {
        $val = array_values($values[$i]);
        $productName[] = $val[0];
        $productImage[] = $val[1];
        $productPrice[] = $val[2];
        $productNum[] = $val[3];
    }
    $merge = array_merge($productImage, $productName, $productPrice, $productNum);
    return $merge;
}
?>

<script>
    function showProducts(values, checked_id) {
        var id = checked_id;

        var btn = document.getElementById(id);
        var flag = $(btn).hasClass('active');
        if (flag == false) {
            values = values.split(",");
            console.log(values);
            var len = values.length;
            var count = len / 4;
            btn.setAttribute("value", "-");
            var node = document.getElementById("product");
            var table = document.createElement("table");
            table.setAttribute("id", "t".concat(id));
            table.setAttribute("border", "1px ");
            table.setAttribute("class", "table table-striped user_table");
            var t = node.appendChild(table);              //body table

            var row1 = document.createElement("tr");
            var r1 = t.appendChild(row1);
            for (var i = 0; i < len / 4; i++) {
                var newcol = document.createElement("td");
                var c1 = r1.appendChild(newcol);
                var img = document.createElement("img");
                img.setAttribute("src", "../../uploads/product/".concat(values[i]));
                img.setAttribute("width", "100px");
                img.setAttribute("hiegth", "100px");
                c1.appendChild(img);
            }


            for (var i = 0; i < 3; i++) {              //# of rows
                var row = document.createElement("tr");
                var r = t.appendChild(row);
                for (var j = 0; j < len / 4; j++) {
                    var newcol = document.createElement("td");
                    var c = r.appendChild(newcol);   //table body tr td
                    if (i == 0) {
                        var txt = document.createTextNode("Product : ".concat(values[count]));
                    }

                    if (i == 1) {
                        var txt = document.createTextNode("Price : ".concat(values[count]));
                    }

                    if (i == 2) {
                        var txt = document.createTextNode("Number : ".concat(values[count]));
                    }

                    c.appendChild(txt);
                    count++;
                }
            }

        }
        else {
            btn.setAttribute("value", "+");
            document.getElementById("t".concat(id)).remove();
        }
        return false;
    }

</script>