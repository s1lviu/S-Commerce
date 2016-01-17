<?php
session_start();
try {
    require_once 'db.php';

    // a new products collection object
    $collection = $db->products;

    // fetch all product documents
    $cursor = $collection->find();

    // How many results found
    $num_docs = $cursor->count();


} catch (MongoConnectionException $e) {
    // if there was an error, we catch and display the problem here
    echo $e->getMessage();
} catch (MongoException $e) {
    echo $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Magazinul lui Silviu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="resources/css/style.css">
    <script src="resources/js/script.js"></script>
    <style>
        #description {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
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
                <li class="active"><a href="index.php">Home</a></li>

                <?php if (isset($_SESSION["account"])) { ?>
                    <li class="active"><a href="account.php">Orders</a></li>
                <?php } else { ?>
                    <li class="active"><a href="account.php">Login</a></li>
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
        <strong>Display</strong>
        <div class="btn-group">
            <a href="#" id="list" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list">
            </span>List</a> <a href="#" id="grid" class="btn btn-default btn-sm"><span
                    class="glyphicon glyphicon-th"></span>Grid</a>
            <a class="btn btn-info btn-sm" href="cart.php"><span id="basket"></span> products in basket</a>
        </div>
    </div>

    <?php if (!isset($_GET["id"])) { ?>
        <div id="products" class="row list-group">
            <?php
            if ($num_docs > 0) {
                // loop over the results
                foreach ($cursor as $obj) {
                    ?>
                    <div class="item  col-xs-4 col-lg-4">
                        <div class="thumbnail">
                            <img class="group list-group-image" src="uploads/<?php echo $obj['image']; ?>" alt=""/>
                            <div class="caption">
                                <h4 class="group inner list-group-item-heading"><a
                                        href="index.php?id=<?php echo $obj['_id']; ?>"><?php echo $obj['title']; ?></a>
                                </h4>
                                <p id="description" class="group inner list-group-item-text">
                                    <?php echo $obj['description']; ?></p>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <p class="lead">
                                            $ <?php echo $obj['price']; ?></p>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <a class="btn btn-success"
                                           onclick=addtocart('<?php echo $obj['_id'] . "','" . urlencode($obj['title']) . "','" . $obj["price"]; ?>')>Add
                                            to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php
                }
            } else {
                // if no products are found, we show this message
                echo "No products found \n";
            }


            ?>
        </div>

    <?php } else {

        $collection = new MongoCollection($db, 'products');
        $sweetQuery = $collection->findOne(array('_id' => new MongoId($_GET["id"])));

        $cursor = $collection->find($sweetQuery);
        foreach ($cursor as $obj) {
            ?>
            <div class="item">
                <div class="thumbnail">
                    <img class="group list-group-image" src="uploads/<?php echo $obj['image']; ?>" alt=""/>
                    <div class="caption">
                        <h4 class="group inner list-group-item-heading"><a
                                href="index.php?id=<?php echo $obj['_id']; ?>"><?php echo $obj['title']; ?></a>
                        </h4>
                        <p class="group inner list-group-item-text">
                            <?php echo $obj['description']; ?></p>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <p class="lead">
                                    $ <?php echo $obj['price']; ?></p>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <a class="btn btn-success" onclick=addtocart("<?php echo $obj['_id']; ?>")>Add to
                                    cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <?php


        }
    }

    // close the connection to MongoDB
    $conn->close();
    ?>
</div>
<script>
    function addtocart(id, name, price) {

        if (localStorage.getItem("ids") != null) {
            localStorage.setItem("ids", localStorage.getItem("ids") + ";" + id);
        } else {
            localStorage.setItem("ids", id);
        }

        if (localStorage.getItem("total_price") != null) {
            localStorage.setItem("total_price", Number(localStorage.getItem("total_price")) + Number(price));
        } else {
            localStorage.setItem("total_price", 0);
            localStorage.setItem("total_price", Number(localStorage.getItem("total_price")) + Number(price));
        }

        localStorage.setItem("names", localStorage.getItem("names") + ";" + name);

        localStorage.setItem("no_products", localStorage.getItem("ids").split(";").length);
        document.getElementById("basket").innerHTML = localStorage.getItem("no_products");

    }

    $(document).ready(function checkout() {
        if (localStorage.ids != null) {
            localStorage.setItem("no_products", localStorage.getItem("ids").split(";").length);
        } else {
            localStorage.setItem("no_products", 0);
        }
        document.getElementById("basket").innerHTML = localStorage.getItem("no_products");
    });
</script>

</body>
</html>