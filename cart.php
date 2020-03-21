<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
            //added to cart
        case "add":
            // check if the quantity passed by the post method is empty or note
            // return true if not empty
            if (!empty($_POST["quantity"])) {
                // run the query tp select the product from tblproduct where the code is 
                // the code we got using the get method
                $productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
                // create an itemArray to store the product retrieved from the database as an array
                $itemArray = array($productByCode[0]["code"] => array('name' => $productByCode[0]["name"], 'code' => $productByCode[0]["code"], 'quantity' => $_POST["quantity"], 'price' => $productByCode[0]["price"], 'image' => $productByCode[0]["image"]));

                //check if the cart_item in the current session is not empty
                // return true if it's not empty
                if (!empty($_SESSION["cart_item"])) {
                    //check if the article already exist in the cart
                    if (in_array($productByCode[0]["code"], array_keys($_SESSION["cart_item"]))) {
                        // loop through the elements of the cart
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            // look for the element that equals the element selected using the query earlier
                            if ($productByCode[0]["code"] == $k) {
                                // if the cart is empty
                                // set the session quantity of the element to 0
                                if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                                    $_SESSION["cart_item"][$k]["quantity"] = 0;
                                }
                                // add the quantity to the old quantity in the session
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                            }
                        }
                    }
                    // if it doesn't exist add it to the cart_item in the session 
                    else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                }
                // if the cart_item in the current session is empty then simply 
                // set it equal to the itemArray 
                else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;

            //if the action is remove from cart
        case "remove":
            // if the cart_item in the session is not empty
            if (!empty($_SESSION["cart_item"])) {
                // loop on each element and unset it from the current session
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if (empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
            // if the action is empty the whole cart
        case "empty":
            // unset the cart_item from the current session
            unset($_SESSION["cart_item"]);
            break;
    }
}
?>



<html xml:lang="fr"> >

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Identification</title>
    <link rel="stylesheet" href="a1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

</head>`

<body style="background-color:black;">

    <!-- nav bar -->
    <nav>

        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" href="#">Active link</a>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-toggle="modal" data-target="#cart">Cart</button>

            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Disabled link</a>
            </li>
        </ul>
     <!--disabled-->
    </nav>
    <!-- end of nav bar -->


    <div id="shopping-cart">

        <a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
        ?>
            <table class="tbl-cart" cellpadding="10" cellspacing="1">
                <tbody>
                    <tr>
                        <th style="text-align:left; color:hotpink" >Name</th>
                        <th style="text-align:left; color:hotpink">Code</th>
                        <th style="text-align:right; color:hotpink" width="5%" >Quantity</th>
                        <th style="text-align:right; color:hotpink" width="10%">Unit Price</th>
                        <th style="text-align:right; color:hotpink" width="10%">Price</th>
                        <th style="text-align:center; color:hotpink" width="5%">Remove</th>
                    </tr>
                    <?php
                    // loop through the element of the cart in the current session and calculate the total of the element*quantity
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                    ?>
                        <tr>
                            <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
                            <td><?php echo $item["code"]; ?></td>
                            <td style="text-align:right; color:hotpink" ><?php echo $item["quantity"]; ?></td>
                            <td style="text-align:right; color:hotpink"><?php echo "$ " . $item["price"]; ?></td>
                            <td style="text-align:right; color:hotpink"><?php echo "$ " . number_format($item_price, 2); ?>
                            </td>
                            <td style="text-align:center;color:hotpink"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.jpg" alt="Remove Item" /></a>
                            </td>
                        </tr>
                    <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    ?>

                    <tr>
                        <td colspan="2" align="right">Total:</td>
                        <td align="right"><?php echo $total_quantity; ?></td>
                        <td align="right" colspan="2">
                            <strong><?php echo "$ " . number_format($total_price, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
        ?>
            <div class="no-records">Your Cart is Empty</div>
        <?php
        }
        ?>
    </div>
</body>

</html>