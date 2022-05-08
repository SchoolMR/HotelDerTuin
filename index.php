<?php
    require 'backend/shared.php';
    session_start();
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

    <title>Hotel Der Tuin | Home</title>
</head>
<body>
        
    <div class="collapse navbar-collapse" id="myNavbar">
        <!-- Print username if it exists, otherwise just don't do anything! -->
        <a class="navbar-brand" id="username" href="#"><?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></a>
        <ul class="nav navbar-nav navbar-right", id="navbarVis">
            <li><a href="index.php">HOME</a></li>
            <li><a href="reserveer.php">RESERVEER</a></li>
            <li><a href="contact.php">CONTACT</a></li>
            
            <!-- Laat overzichten zien als medewerker is ingelogd -->
            <?php if(isset($_SESSION['username'])): ?>
                <li><a href="overzicht.php">ADMIN OVERZICHT</a></li>
            <?php endif; ?>

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

    <div class="homepageImageDiv">
        <img src="images/entree-hotel-tatenhove.jpg" class="homepageImage"> 
    </div>

    <p id="mainInfo">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Aliquet risus feugiat in ante metus dictum at. 
        Amet est placerat in egestas erat imperdiet sed euismod nisi. 
        Tempor nec feugiat nisl pretium. 
        Nulla aliquet porttitor lacus luctus accumsan tortor posuere. 
        Tempus egestas sed sed risus pretium quam. Nibh cras pulvinar mattis nunc sed blandit. 
        Massa ultricies mi quis hendrerit dolor magna eget est lorem. Ut morbi tincidunt augue interdum velit euismod. 
        A erat nam at lectus urna. Posuere lorem ipsum dolor sit amet consectetur. Non diam phasellus vestibulum lorem. 
        Purus in massa tempor nec feugiat nisl pretium fusce. 
        Libero nunc consequat interdum varius sit amet mattis. Nam aliquam sem et tortor consequat id.</p>

</body>
</html>