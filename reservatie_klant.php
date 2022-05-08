<?php
    require 'backend/shared.php';
    include_once 'backend/database.php';
    session_start();

    if(isset($_POST['submit']))
    {
        $db = new database('localhost', 'root', '', 'hoteldertuin');

        if($db->getKamerBeschikbaarheid($_POST['kamernummer']) == 0 || $db->getKamerBeschikbaarheid($_POST['kamernummer']) == false || empty($db->getKamerBeschikbaarheid($_POST['kamernummer'])))
        {
            $_SESSION['beschikbaarheid'] = false;
            $continue = false;
        }
        else
        {
            $_SESSION['beschikbaarheid'] = true;
        }

        if(!isset($continue))
        {
            $kamer_id = $db->updateKamerBeschikbaarheid($_POST['kamernummer']);
            $klant_id = $db->insertKlant($_POST['klantnaam'], $_POST['adres'], $_POST['plaats'], $_POST['postcode'], $_POST['telnummer']);
            $db->insertReservering($kamer_id, $klant_id, $_POST['begindatum'], $_POST['einddatum']);
        }
    }
    else
    {
        unset($_SESSION['beschikbaarheid']);
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

    <title>Hotel Der Tuin | Reserveer</title>
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

    <?php if(isset($_SESSION['beschikbaarheid']) && $_SESSION['beschikbaarheid'] == false): ?>
        <div class="login">
            <p class="warning">Deze kamer bestaat niet of is niet beschikbaar.</p>
        </div>
    <?php endif; ?>

    <p id="mainInfoContact">Reserveer</p>

    <div class="login">
        <form method="POST">
            <label class="labelForm">Naam</label>
            <input class="loginInput" type="text" name="klantnaam" placeholder="Mike Reule" required><br>
            <label class="labelForm">Adres</label>
            <input class="loginInput" type="text" name="adres" placeholder="Sportstraat 39 2" required><br>
            <label class="labelForm">Woonplaats</label>
            <input class="loginInput" type="text" name="plaats" placeholder="Amsterdam" required><br>
            <label class="labelForm">Postcode</label>
            <input class="loginInput" type="text" name="postcode" placeholder="1076TR" required><br>
            <label class="labelForm">Telefoonnummer</label>
            <input class="loginInput" type="text" name="telnummer" placeholder="06123456789" required><br>
            <label class="labelForm">Startdatum</label>
            <input class="loginInput" type="date" name="begindatum" required><br>
            <label class="labelForm">Einddatum</label>
            <input class="loginInput" type="date" name="einddatum" required><br>
            <label class="labelForm">Kamernummer</label>
            <input class="loginInput" type="number" name="kamernummer" placeholder="Kamernummer" required><br>
            <input class="loginButton" type="submit" value="Submit" name="submit">
        </form>
    </div>

</body>
</html>