<?php
session_start();
if ($_SESSION["account"] != "569b89108cca299727008360") {
    header("Location: ../index.php");
}

if (isset($_POST["title"]) && isset($_FILES["fileToUpload"])) {
    try {
        require_once '../db.php';


        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

// Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../uploads/" . $newfilename);
        }


        // a new products collection object
        $collection = $db->products;

        // Create an array of values to insert
        $title = (isset($_POST["title"]) ? $_POST["title"] : $title = '0');
        $description = (isset($_POST["description"]) ? $_POST["description"] : $description = '0');
        $price = (isset($_POST["price"]) ? $_POST["price"] : $price = '0');

        $product = array(
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'image' => $newfilename
        );

        // insert the array
        $collection->insert($product);


        // close connection to MongoDB
        $conn->close();

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
    <title>S-Commerce Administration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        .container {
            margin-top: 20px;
        }

        .image-preview-input {
            position: relative;
            overflow: hidden;
            margin: 0px;
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }

        .image-preview-input input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }

        .image-preview-input-title {
            margin-left: 2px;
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
            <a class="navbar-brand" href="../index.php">S-Commerce</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../index.php">Home</a></li>
                <li class="active"><a href="manage_products.php">Manage products</a></li>
                <?php if (isset($_SESSION["account"])) { ?>
                    <li class="active"><a href="../account.php">Orders</a></li>
                <?php } else { ?>
                    <li class="active"><a href="../account.php">Login</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <?php if (isset($_SESSION["account"])) { ?>
                    <li><a href="../account.php?logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <h2>Add a product</h2>
        <form role="form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email">Titlu produs:</label>
                <input required name="title" type="text" class="form-control" id="email" placeholder="Product name">
            </div>
            <div class="form-group">
                <label for="email">Descriere produs:</label>
            <textarea required name="description" type="text" class="form-control" id="email"
                      placeholder="Product description"></textarea>
            </div>
            <div class="form-group">
                <label for="email">Pret produs:</label>
                <input required name="price" type="number" class="form-control" id="email" placeholder="Product price">
            </div>


            <div class="col-xs-12 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <!-- image-preview-filename input [CUT FROM HERE]-->
                <div class="input-group image-preview">
                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                    <!-- don't give a name === doesn't send on POST/GET -->
                <span class="input-group-btn">
                    <!-- image-preview-clear button -->
                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                        <span class="glyphicon glyphicon-remove"></span> Clear
                    </button>
                    <!-- image-preview-input -->
                    <div class="btn btn-default image-preview-input">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span class="image-preview-input-title">Browse</span>
                        <input type="file" accept="image/png, image/jpeg, image/gif" name="fileToUpload"/>
                        <!-- rename it -->
                    </div>
                </span>
                </div><!-- /input-group image-preview [TO HERE]-->
            </div>

    </div>
    <button type="submit" class="btn btn-default">Save product</button>
    </form>
</div>

<script>
    $(document).on('click', '#close-preview', function () {
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
                $('.image-preview').popover('show');
            },
            function () {
                $('.image-preview').popover('hide');
            }
        );
    });

    $(function () {
        // Create the close button
        var closebtn = $('<button/>', {
            type: "button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class", "close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger: 'manual',
            html: true,
            title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
            content: "There's no image",
            placement: 'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function () {
            $('.image-preview').attr("data-content", "").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("Browse");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function () {
            var img = $('<img/>', {
                id: 'dynamic',
                width: 250,
                height: 200
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("Change");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content", $(img)[0].outerHTML).popover("show");
            }
            reader.readAsDataURL(file);
        });
    });
</script>
</body>
</html>

