<?php
require '../database.php';
session_start();

if (isset($_POST['delete'])) {
    $deleteProductId = $_POST['deleteProductId'];

    foreach ($_SESSION['cart'] as $key => $product) {
        if ($product['productId'] == $deleteProductId) {
            unset($_SESSION['cart'][$key]);

            echo "<script>alert('Product with ID {$deleteProductId} has been removed');</script>";
            break;
        }
    }
}

if (isset($_POST['buy'])) {
    // Product details
    $productId = $_POST['productId'];
    $productName = $_POST['name'];
    $productPrice = $_POST['price'];
    $quantity = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize an empty cart
    }

    // Check if the product already exists in the cart
    $productExists = false;
    foreach ($_SESSION['cart'] as &$product) {
        if ($product['productId'] == $productId) {
            $product['quantity'] += 1; // Increment quantity if the product already exists
            $productExists = true;
            break;
        }
    }
    // If the product is not in the cart, add it
    if (!$productExists) {
        $_SESSION['cart'][] = [
            'productId' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => $quantity,
        ];
        $productExists = true;
    }
    //echo '<br> Product added to cart.';
    //header( "Location: ?page=home");
    //die();

}

if (isset($_POST['checkout'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobilenumber = $_POST['mobilenumber'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];
    $fullName = $firstname . ' ' . $lastname;

    // SELECT query to check if the user exist or not 
    $sqlIsExist = "SELECT * FROM `Users` WHERE name = '$fullName' AND email = '$email'";
    $resultUsers = $connection->query($sqlIsExist);
    if ($resultUsers->num_rows > 0) {
        echo "<script>alert('Username and eamil already exist, try to log in!');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo $hashedPassword;
        $sqlCustomers = "INSERT INTO Customers (firstname, lastname, mobilenumber, adress, postcode, country, epost) VALUES 
                ('$firstname', '$lastname', $mobilenumber,'$address', $postcode,'$country', '$email')";
        $connection->query($sqlCustomers);
        $customer_id = $connection->insert_id;

        $sqlUsers = "INSERT INTO Users (name, email, password, customer_id) 
                        VALUES ('$fullName', '$email', '$hashedPassword', $customer_id)";
        $connection->query($sqlUsers);

        $total_amount = 0;
        foreach ($_SESSION['cart'] as $product) {
            $total_amount += $product['price'] * $product['quantity'];
        }
        //echo $total_amount;
        //print_r($_SESSION['cart']);                        
        //echo 'Ordered, ' . date("Y/m/d");

        $sqlOrders = "INSERT INTO Orders (status, order_date, total_amount, customer_id) 
                    VALUES ('Ordered', CURRENT_DATE(), $total_amount, $customer_id)";
        $connection->query($sqlOrders);
        $order_id = $connection->insert_id;

        $result = $connection->query("SELECT id FROM Orders WHERE id = $order_id");
        if ($result->num_rows > 0) {
            foreach ($_SESSION['cart'] as $product) {
                $productQuantity = $product['quantity'];
                $amount = $product['price'];
                $product_id = $product['productId'];
                $sqlOrder_lines = "INSERT INTO Order_lines (quantity, amount, order_id, product_id) 
                VALUES ($productQuantity, $amount, $order_id, $product_id)";
                echo $sqlOrder_lines;
                $connection->query($sqlOrder_lines);
            }
        }
        echo "<script>alert('We have received your order :)');</script>";
        unset($_SESSION['cart']);
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klasses Getosts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand navbarHeader">Klasses Getosts</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=cart">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Log in</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // $hashedcheese = password_hash('cheese', PASSWORD_DEFAULT);
    // $sql =  "INSERT INTO Users (name, email, password, type) 
    //                 VALUES ('klasse', 'klasse.klasse@example.com', '$hashedcheese','admin')";
    //                 $connection->query($sql);
                    
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        if ($page == 'cart') {
            // Display the cart contents
            if (isset($_SESSION['cart'])) {
                echo "<h3>Cart Contents:</h3>";

                foreach ($_SESSION['cart'] as $product) {
                    echo "Product ID: {$product['productId']}";
                    echo " Name: {$product['name']}";
                    echo " Price: {$product['price']} SEK";
                    echo " Quantity: {$product['quantity']}";
                    ?>
                    <form method='POST' style="display: inline; float:left; margin-right:2rem">
                        <input type='hidden' name='deleteProductId' value=<?= $product['productId'] ?>>
                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                    </form>
                    <hr>
                    <?php
                }
            }
            ?>
            <form action="" method="POST" class="row g-3">
                <div class="col-md-4">
                    First name : <input type="text" name="firstname" class="form-control" id="validationDefault01"
                        placeholder="Helena" required>
                </div>
                <div class="col-md-4">
                    Last name : <input type="text" name="lastname" class="form-control" id="validationDefault02"
                        placeholder="Alfred" required>
                </div>
                <div class="col-md-4">
                    Email :
                    <div class="input-group">
                        <input type="text" name="email" class="form-control" id="validationDefaultUsername"
                            aria-describedby="inputGroupPrepend2" required placeholder="example@gmail.com">
                    </div>
                </div>
                <div class="col-md-4">
                    Password :
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="validationDefaultUsername"
                            aria-describedby="inputGroupPrepend2" placeholder="***">
                    </div>
                </div>
                <div class="col-md-6">
                    City : <input type="text" name="country" class="form-control" id="validationDefault03"
                        placeholder="Kristianstad" required>
                </div>
                <div class="col-md-3">
                    Postcode : <input type="text" name="postcode" class="form-control" id="validationDefault05"
                        placeholder="26193" required>
                </div>
                <div class="col-md-3">
                    Mobilenumber : <input type="text" name="mobilenumber" class="form-control" id="validationDefault05"
                        placeholder="079***" required>
                </div>
                <div class="col-md-3">
                    Adress: <input type="text" name="address" class="form-control" id="validationDefault05"
                        placeholder="123 Oak Street" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit" name="checkout">Checkout</button>
                </div>
            </form>
            <?php


        } else if ($page == 'home') {
            $sql = "SELECT p.id, p.name, p.price, p.description, GROUP_CONCAT(CONCAT(i.image_path, '|', i.alt_text)) AS image_paths
                    FROM Products p
                    LEFT JOIN Product_Images i ON p.id = i.product_id
                    GROUP BY p.id;";
            $result = $connection->query($sql);
            ?>
                <div class="container mt-4">
                    <!-- Flex container for all cards -->
                    <div class="d-flex flex-wrap justify-content-between">
                        <?php
                        $productId;
                        while ($row = $result->fetch_assoc()) {
                            $productId = $row['id'];
                            // Assume `image_paths` contains multiple image URLs as a comma-separated string.
                            $images = explode(',', $row['image_paths']); // Split the string into an array of image paths.
                            ?>
                            <div class="card m-2" style="width: 22rem;">
                            <?php if (!empty($images[0])) { // Check if there are any images ?>
                                    <div id="carouselExample<?= $productId ?>" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php
                                            foreach ($images as $index => $imageData) {
                                                // Safely split the image data into path and alt text
                                                $imageParts = explode('|', $imageData);
                                                $imagePath = $imageParts[0] ?? 'placeholder.jpg'; // Default image if none provided
                                                $altText = $imageParts[1] ?? 'No description available'; // Default alt text if none provided
                            
                                                ?>
                                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                    <img src="<?= $imagePath ?>" class="d-block w-100" alt="<?= $altText ?>">
                                                </div>
                                            <?php
                                            } ?>
                                        </div>
                                        <!-- Carousel Controls -->
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselExample<?= $productId ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselExample<?= $productId ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                            <?php } else { ?>
                                    <!-- Placeholder image for products with no images -->
                                    <img src="placeholder.jpg" class="card-img-top" alt="No image available">
                            <?php } ?>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <form method="POST">
                                        <h5 class="card-title"><?= $row['name'] ?></h5>
                                        <input type="hidden" name="name" value="<?= $row['name'] ?>">
                                        <p class="card-text"><?= $row['description'] ?></p>
                                        <b>
                                            <p class="card-text"><?= $row['price'] ?> SEK </p>
                                            <input type="hidden" name="price" value="<?= $row['price'] ?>">
                                        </b>
                                        <br>
                                        <!-- Hidden input field for the product ID -->
                                        <input type="hidden" name="productId" value="<?= $row['id'] ?>">
                                        <input type="submit" class="btn btn-success" name="buy" value="BUY">
                                    </form>
                                </div>
                            </div>
                        <?php
                        }
        }
    }
    ?>


</body>

</html>