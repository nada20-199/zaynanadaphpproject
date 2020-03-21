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
<title>Meilleur Parfum</title>
<body style="background-color:black;">

    <!-- nav bar -->
    <nav>

        <ul class="nav justify-content-center"  style="background-color:white;">
            <li class="nav-item">
                <a class="nav-link active" href="cart.php">PANIER</a>
            </li>
    
            <li class="nav-item">
                <a class="nav-link " href="login.php">S'INSCRIRE</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="register.php">REGISTRE</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link " href="contactus.php">CONTACTEZ-NOUS</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link " href="products.php">LES PRODUITS</a>
            </li>
        </ul>

    </nav>
    <!-- end of nav bar -->


    <!--  -->
   <!--  --><!--  -->

    <div>



        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="nada.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="nido.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="zaina.jpg" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>


    </div>
    <!-- <a href="hayat.jpg"> -->

    <!-- <img src="hayat.jpg" alt="ma photo" height="30%" width="32%"> -->
    <!-- <a href="d.jpg"> -->
    <!-- <img src="d.jpg" alt="ma photo" height="30%" width="35%"> -->
    <!-- <a href="o.jpg"> -->
    <!-- <img src="o.jpg" alt="ma photo" height="30%" width="32%"> -->

    
  


    <!-- atlou7i koulchi fi 000webhost ou aykhdem lik  -->
    <!-- ui daba ay fichier bedelti mennou kat7b9ay tlou7ih gha bou7dou machi koulchi  -->
    <!-- daba m3a bedelti douk les image khwitihoum ou jme3tihoum atlou7i koulchi  -->
    <!-- tal lik ou npasi lik kifach atdiri bach treddi hadchi dynamic  -->


</body>

</html>