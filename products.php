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
                <a class="nav-link active" href="cart.php">Cart</a>
            </li>
    
            <li class="nav-item">
                <a class="nav-link active" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="register.php">Register</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link active" href="contactus.php">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="products.php">Products</a>
            </li>
        </ul>

    </nav>
    <!-- end of nav bar -->


   <div class="container">

  <!-- Items from database -->
  <div id="product-grid">
        <div class="txt-heading" style="background-color:deeppink;"><h1>Products</h1></div>
        <?php
        // tblproduc is the table where all the articles are stored

        // select all the articles in the tblproduct table and order them by id 
        // store them in a $product_array
        $product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");

        // true if the $product_array is not empty
        if (!empty($product_array)) {

            // loop through all the element of the $product_array
            foreach ($product_array as $key => $value) {
                // the html element filled dynamically with each element of $product_array
        ?>
                <div class="product-item">
                    <form method="post" style="color:deeppink;" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                        <div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
                        <div class="product-tile-footer">
                            <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
                            <div class="product-price"><?php echo "MAD " . $product_array[$key]["price"]; ?></div>
                            <div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
                        </div>
                    </form>
                </div>
        <?php
            }
        }
        ?>
    </div>


   </div>

</body>

</html>