<?php
    require '../backend/shared.php';
    require '../backend/database.php';
    session_start();

    $db = new database('localhost', 'root', '', 'hoteldertuin');
    
    // Failsafe zodat mensen die niet zijn ingelogd dit niet kunnen zien!
    if(!isset($_SESSION['username']))
    {
        header('Location: index.php');
    }

    if(isset($_POST))
    {
        if(isset($_POST['submitKamer']))
        {
            $db->updateKamer($_GET['kamer_id'], $_POST['kamer_nummer'], $_POST['beschikbaarheid']);
        }
        else if(isset($_POST['submitKlant']))
        {
            $db->updateKlant($_GET['klant_id'], $_POST['klant_naam'], $_POST['klant_adres'], $_POST['klant_plaats'], $_POST['klant_postcode'], $_POST['klant_telnummer']);
        }
        else if(isset($_POST['submitReservering']))
        {
            $db->updateReservering($_GET['reservering_id'], $_POST['begin_datum'], $_POST['eind_datum']);
        }   
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
    <link rel="stylesheet" href="../styles.css">

    <title>Hotel Der Tuin | Admin Edit</title>
</head>
<body>
        
    <div class="collapse navbar-collapse" id="myNavbar">
        <!-- Print username if it exists, otherwise just don't do anything! -->
        <a class="navbar-brand" id="username" href="#"><?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></a>
        <ul class="nav navbar-nav navbar-right", id="navbarVis">
            <li><a href="../index.php">HOME</a></li>
            <li><a href="../reserveer.php">RESERVEER</a></li>
            <li><a href="../contact.php">CONTACT</a></li>

            <!-- Laat overzichten zien als medewerker is ingelogd -->
            <?php if(isset($_SESSION['username'])): ?>
                <li><a href="../overzicht.php">ADMIN OVERZICHT</a></li>
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

    <?php if(isset($_GET['reservering_id'])):
        $reserveringInfo = $db->sqlSelect('SELECT * FROM reservering WHERE reservering_id = ' .$_GET['reservering_id']);    
        foreach($reserveringInfo as $arr): 
    ?>                     
        <div class="login">
            <form method="POST">
                <label class="labelForm">Begin Datum</label>
                <input class="loginInput" type="date" name="begin_datum" value=<?= $arr['begin_datum']; ?> required><br>
                <label class="labelForm">Eind Datum</label>
                <input class="loginInput" type="date" name="eind_datum" value=<?= $arr['eind_datum']; ?> required><br>
                <input class="loginButton" type="submit" value="Update" name="submitReservering">
            </form>
        </div>
    <?php endforeach; endif; ?>
    
    
    <?php if(isset($_GET['kamer_id'])):
        $kamerInfo = $db->sqlSelect('SELECT * FROM kamer WHERE kamer_id = ' .$_GET['kamer_id']);    
        foreach($kamerInfo as $arr): 
    ?>                     
        <div class="login">
            <form method="POST">
                <label class="labelForm">Kamer Nummer</label>
                <input class="loginInput" type="text" name="kamer_nummer" value="<?= $arr['kamer_nummer']; ?>" required><br>
                <label class="labelForm">Kamer Beschikbaarheid</label>
                <input class="loginInput" type="text" name="beschikbaarheid" value="<?= $arr['beschikbaarheid']; ?>" required><br>
                <input class="loginButton" type="submit" value="Update" name="submitKamer">
            </form>
        </div>
    <?php endforeach; endif; ?>
    
    <?php if(isset($_GET['klant_id'])):
        $klantInfo = $db->sqlSelect('SELECT * FROM klant WHERE klant_id = ' .$_GET['klant_id']);    
        foreach($klantInfo as $arr): 
    ?>                     
        <div class="login">
            <form method="POST">
                <label class="labelForm">Klant Naam</label>
                <input class="loginInput" type="text" name="klant_naam" value="<?= $arr['naam']; ?>" required><br>
                <label class="labelForm">Klant Adres</label>
                <input class="loginInput" type="text" name="klant_adres" value="<?= $arr['adres']; ?>" required><br>
                <label class="labelForm">Klant Plaats</label>
                <input class="loginInput" type="text" name="klant_plaats" value="<?= $arr['plaats']; ?>" required><br>
                <label class="labelForm">Klant Postcode</label>
                <input class="loginInput" type="text" name="klant_postcode" value="<?= $arr['postcode']; ?>" required><br>
                <label class="labelForm">Klant Telefoonnummer</label>
                <input class="loginInput" type="text" name="klant_telnummer" value="<?= $arr['telefoonnummer']; ?>" required><br>
                <input class="loginButton" type="submit" value="Update" name="submitKlant">
            </form>
        </div>
    <?php endforeach; endif; ?>
    

</body>
</html>