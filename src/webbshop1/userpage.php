<?php
require '../database.php';
session_start();

if (isset($_GET['deleteOrder'])) {
    $sql = 'DELETE FROM Orders WHERE id = ' . $_GET['deleteOrder'];
    //echo $sql;
    $connection->query($sql);
}

if (isset($_POST['update_status'])) {
    $status_id = $_POST['statusId'];
    $status = $_POST['status'];
    
    $sql = "UPDATE Orders SET status = '$status' WHERE id = $status_id";
    $result = $connection->query($sql);
}

if (isset($_POST['logout'])) {
    $_SESSION['loggedin'] = false;
    header('Location: login.php');
    //exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                        <a class="nav-link active" aria-current="page" href="index.php?page=home">Home</a>
                    </li>
                    <?php
                    if ($_SESSION['loggedin'] == true ){
                        if ($_SESSION['type'] == 'admin'){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link">My Page</a>
                    </li>
                    <?php
                        }else{
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=cart">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"> <?= $_SESSION['name'] ?></a>
                    </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <h1>Welcome back <?= $_SESSION['name'] ?></h1>
    <form method="POST">
        <input type="submit" class="btn btn-secondary" name="logout" value="Log out">
    </form>
    <?php
    if ($_SESSION['type'] === 'admin') {
        $sql = "SELECT o.id, o.total_amount, o.status, o.order_date, c.firstname, c.country
                FROM Orders o
                JOIN Customers c ON c.id = o.customer_id
                GROUP BY c.firstname, o.id, o.total_amount, o.status, o.order_date, c.country
                ORDER BY o.order_date";
        $result = $connection->query($sql);
        ?>
        <div class="container mt-4">
            <div class="row">
                <?php
                $completedOrders = [];
                $activeOrders = [];
                while ($row = $result->fetch_assoc()) {
                    if ($row['status'] === 'Paid') {
                        $completedOrders[] = $row; // Add to completed orders
                    } else {
                        $activeOrders[] = $row; // Add to active orders
                    }
                }
                ?>

                <!-- Display Active Orders -->
                <div class="container mt-4">
                    <h3>Active Orders</h3>
                    <div class="row">
                        <?php foreach ($activeOrders as $order) { ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card text-bg-light" style="max-width: 18rem;">
                                    <div class="card-header">#Order <?= $order['id'] ?>
                                        <a class="material-icons" style='margin-left:5rem; color:red; text-decoration: none;'
                                            href="?deleteOrder=<?= $order['id'] ?>">&#xe872;</a>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Name : <?= $order['firstname'] ?></h5>
                                        <p class="card-text">Total amount : <?= $order['total_amount'] ?> kr</p>
                                        <p class="card-text">Country : <?= $order['country'] ?></p>
                                        <p class="card-text">Status : <?= $order['status'] ?></p>
                                        <p class="card-text">Date : <?= $order['order_date'] ?></p>
                                        <form method="POST">
                                            <input type="hidden" name="statusId" value="<?= $order['id'] ?>">
                                            <select name='status'>
                                                <option value='Ordered'>Ordered</option>
                                                <option value='Packed'>Packed</option>
                                                <option value='Shipped'>Shipped</option>
                                                <option value='Paid'>Paid</option>
                                            </select>
                                            <input type="submit" value="Update" name="update_status" class="btn btn-warning">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Display Completed Orders -->
                <div class="container mt-4">
                    <h3>Completed Orders</h3>
                    <div class="row">
                        <?php foreach ($completedOrders as $order) { ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card text-bg-success" style="max-width: 18rem;">
                                    <div class="card-header">#Order <?= $order['id'] ?></div>
                                    <div class="card-body">
                                        <h5 class="card-title">Name : <?= $order['firstname'] ?></h5>
                                        <p class="card-text">Total amount : <?= $order['total_amount'] ?> kr</p>
                                        <p class="card-text">Status : <?= $order['status'] ?></p>
                                        <p class="card-text">Date : <?= $order['order_date'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                ?>
            </div>
        </div>
        <?php
    } else if ($_SESSION['type'] === 'customer') {
        $username = $_SESSION['name'];
        $sql = "SELECT o.id, o.total_amount, o.status, o.order_date
                FROM Orders o
                JOIN Users u ON u.customer_id = o.customer_id
                WHERE u.name = '$username'
                ORDER BY o.order_date";
        $result = $connection->query($sql);
        $completedOrders = [];
        $activeOrders = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['status'] === 'Paid') {
                $completedOrders[] = $row; // Add to completed orders
            } else {
                $activeOrders[] = $row; // Add to active orders
            }
        }
        ?>
        <!-- Display Active Orders -->
        <div class="container mt-4">
            <h3>Active Orders</h3>
            <div class="row">
                <?php foreach ($activeOrders as $order) { ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card text-bg-light" style="max-width: 18rem;">
                            <div class="card-header">#Order <?= $order['id'] ?></div>
                            <div class="card-body">
                                <p class="card-text">Total amount : <?= $order['total_amount'] ?> kr</p>
                                <p class="card-text">Status : <?= $order['status'] ?></p>
                                <p class="card-text">Date : <?= $order['order_date'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Display Completed Orders -->
        <?php
        if (count($completedOrders) > 0) {
            ?>
            <div class="container mt-4">
                <h3>Order History</h3>
                <div class="row">
                    <?php
                    foreach ($completedOrders as $order) { ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-bg-success" style="max-width: 18rem;">
                                <div class="card-header">#Order <?= $order['id'] ?></div>
                                <div class="card-body">
                                    <p class="card-text">Total amount : <?= $order['total_amount'] ?> kr</p>
                                    <p class="card-text">Status : <?= $order['status'] ?></p>
                                    <p class="card-text">Date : <?= $order['order_date'] ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
        }

    }
    ?>
</body>

</html>