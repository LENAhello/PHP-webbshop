<?php
require '../database.php';
session_start();

if (isset($_POST["login"])) {

    // Prepare a SQL statement to securely query the database for the user details
    if($stmt = $connection->prepare('SELECT type, password, customer_id FROM Users WHERE name = ?')) {
       
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        // Check if a user with the given username exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($type, $password, $id);
            $stmt->fetch();
            
            $isValid = password_verify( $_POST['password'], $password);
            if (!$isValid) {
                echo "<script>alert('Invalid username or password, please try again!');</script>";
            }else{ // If the password is correct set up the user session
                $_SESSION['loggedin'] = true;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['type'] = $type;
                $_SESSION['id'] = $id;
                header('location: userpage.php');
            }
        }else{
            echo "<script>alert('Invalid username or password, please try again!');</script>";
        }       
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=cart">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Log in</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    if ($_SESSION['loggedin'] == false) { 
        ?>
        <div class="container mt-5">
            <h2 class="text-center">Log in</h2>
            <form action="login.php" method="POST" class="mt-4 mx-auto" style="max-width: 400px;">
                <div class="mb-3">
                    <label for="email" class="form-label">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" name="login">Log in</button>
            </form>
        </div>
    <?php
    } 
    ?>
</body>
</body>

</html>