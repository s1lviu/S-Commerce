<?php
session_start();
if (!isset($_SESSION["account"]))
    header("Location: account.php");

if (isset($_POST["ids"])) {
    try {
        require_once 'db.php';


        // a new products collection object
        $collection = $db->orders;

        // Create an array of values to insert
        $ids = (isset($_POST["ids"]) ? $_POST["ids"] : $ids = null);

        $order = array(
            'user_id' => $_SESSION["account"],
            'ids' => $ids

        );

        // insert the array
        $collection->insert($order);


        // close connection to MongoDB
        $db->close();

    } catch (MongoConnectionException $e) {
        // if there was an error, we catch and display the problem here
        echo $e->getMessage();
    } catch (MongoException $e) {
        echo $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Shopping Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="resources/css/style.css">
    <script src="resources/js/script.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">S-Commerce</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>

                <?php if (isset($_SESSION["account"])) { ?>
                    <li><a href="account.php">Orders</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <?php if (isset($_SESSION["account"])) { ?>
                    <li><a href="account.php?logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="well well-sm">
        <div class="btn-group">
            <a onclick="reset();" href="#">Empty cart</a>
        </div>
    </div>


    <ul id="basket_list" class="list-group">
    </ul>
    <p id="total">Total: $</p>
    <button id="checkout">Checkout</button>
</div>
<script>
    function reset() {
        localStorage.removeItem('ids');
        localStorage.removeItem('names');
        localStorage.removeItem('total_price');
        localStorage.removeItem('no_products');
        alert("No more products in your cart!");
        window.location = "index.php";
    }

    var names = localStorage.getItem("names").split(";");
    for (var i = 1; i < names.length; i++)
        $("#basket_list").append('<li class="list-group-item">' + decodeURIComponent((names[i] + '').replace(/\+/g, '%20')) + '</li>');

    $("#total").append(localStorage.getItem("total_price"));


    $("#checkout").click(function () {
        var ids = localStorage.getItem("ids");
        $.post("cart.php",
            {
                ids: ids
            },
            function () {
                alert("Success! Your order has been processed!");
                localStorage.removeItem('ids');
                localStorage.removeItem('names');
                localStorage.removeItem('total_price');
                localStorage.removeItem('no_products');
                window.location = "account.php";
            });
    });


</script>

</body>
</html>