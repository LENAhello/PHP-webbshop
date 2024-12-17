<?php
    require '../database.php';
   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand navbarHeader">Klasses Getosts</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Log in</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <?php

    /*function table($res)
    {
        $header = false;
        echo "<table border='1'>";
        while ($row = $res->fetch_assoc()) {

            if (!$header) {
                ?>
                <tr>
                    <?php
                    foreach ($row as $key => $val) {
                        echo "<th> $key </th>";
                    }
                    ?> 
                </tr>
                <?php
                $header = true;
            }
            echo "<tr>";
            foreach ($row as $key => $val) {
                echo "<td> $val </td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    $sql = "SELECT * FROM Orders";
    $result = $connection->query($sql);
    table($result);*/
    ?>

    <form action="" method="POST" class="row g-3">

        <div class="col-md-4">
            First name : <input type="text" class="form-control" id="validationDefault01" placeholder="Helena" required>
        </div>
        <div class="col-md-4">
            Last name : <input type="text" class="form-control" id="validationDefault02" placeholder="Alfred" required>
        </div>
        <div class="col-md-4">
            Email :
            <div class="input-group">
                <input type="text" class="form-control" id="validationDefaultUsername"
                    aria-describedby="inputGroupPrepend2" required placeholder="example@gmail.com">
            </div>
        </div>
        <div class="col-md-6">
            City : <input type="text" class="form-control" id="validationDefault03" placeholder="Kristianstad" required>
        </div>
        <div class="col-md-3">
            Postcode : <input type="text" class="form-control" id="validationDefault05" placeholder="26193" required>
        </div>
        <div class="col-md-3">
            Mobilenumber : <input type="text" class="form-control" id="validationDefault05" placeholder="079***"
                required>
        </div>
        <div class="col-md-3">
            Adress: <input type="text" class="form-control" id="validationDefault05" placeholder="123 Oak Street"
                required>
        </div>

        <!-- Product Details -->
        <div class="col-md-4">
            <input type="hidden" name="product_id" value="2"> <!-- Example Product ID -->
            Quantity : <input class="form-control" type="number" name="quantity" id="quantity" value="1" min="1"
                required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Checkout</button>
        </div>
    </form>
</body>

</html>