<?php

    require 'backend/shared.php';
    require 'backend/database.php';
    session_start();

    // Make sure they get sent back to home page if they're logged in and trying to access the login page!
    if(isset($_SESSION['username']))
    {
        header('Location: index.php');
        exit;
    }
    
    if(isset($_POST['submit']))
    {
        $db = new database('localhost', 'root', '', 'hoteldertuin');
        $db->loginLogic($_POST['username'], $_POST['password']);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css">

    <title>Hotel Der Tuin | Login</title>
</head>
<body>
    
    <div class="collapse navbar-collapse" id="myNavbar">
        <!-- Print username if it exists, otherwise just don't do anything! -->
        <a class="navbar-brand" id="username" href="#"><?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></a>
        <ul class="nav navbar-nav navbar-right", id="navbarVis">
            <li><a href="index.php">HOME</a></li>
            <li><a href="reserveer.php">RESERVEER</a></li>
            <li><a href="contact.php">CONTACT</a></li>

            <?php
                // Verschillend text en functies voor login/logout-
                // we willen niet aan iemand die al ingelogd is vragen of ze willen inloggen
                $loginData = array(
                    'func' => isset($_SESSION['username']) ? 'logout.php' : 'login.php',
                    'text' => isset($_SESSION['username']) ? 'LOGOUT' : 'LOGIN'
                );
            ?>

            <li><a href="<?= $loginData['func'] ?>"><?= $loginData['text'] ?></a></li>
        </ul>
    </div>

    <div class="login">
        <form method="POST">
            <input class="loginInput" type="text" name="username" placeholder="Username" required><br>
            <input class="loginInput" type="text" name="password" placeholder="Password" required><br>
            <input class="loginButton" type="submit" value="Login" name="submit">
        </form>
    </div>

</body>
</html>