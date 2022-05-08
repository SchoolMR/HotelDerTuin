<?php
    require 'backend/shared.php';
    require 'backend/database.php';
    session_start();

    $db = new database('localhost', 'root', '', 'hoteldertuin');

    // Failsafe zodat mensen die niet zijn ingelogd dit niet kunnen zien!
    if(!isset($_SESSION['username']))
    {
        header('Location: index.php');
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

    <title>Hotel Der Tuin | Admin Overzicht</title>
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

    <?php 
        $reserveringInfo = $db->sqlSelect('SELECT * FROM reservering');
        $klantInfo = $db->sqlSelect('SELECT * FROM klant');
        $kamerInfo = $db->sqlSelect('SELECT * FROM kamer');
    ?>

    <!-- Reservering overzicht -->
    <table class="overzicht">
        <thead>
            <tr>
                <th scope="col" class="overzichtHead">Reservering ID</th>
                <th scope="col" class="overzichtHead">Kamer ID</th>
                <th scope="col" class="overzichtHead">Klant ID</th>
                <th scope="col" class="overzichtHead">Begin Datum</th>
                <th scope="col" class="overzichtHead">Eind Datum</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach($reserveringInfo as $arr): ?>
                    <tr>
                        <td class="overzichtHead"><?php if(!empty($arr['reservering_id'])) echo $arr['reservering_id']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['kamer_id'])) echo $arr['kamer_id']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['klant_id'])) echo $arr['klant_id']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['begin_datum'])) echo $arr['begin_datum']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['eind_datum'])) echo $arr['eind_datum']; ?></td>
                        <td class="overzichtBtn">
                            <a class="btn btn-primary mr-2 btn-sm" href="crud/edit.php?reservering_id=<?= $arr["reservering_id"]; ?>">Edit</a>
                        </td>  
                        <td class="overzichtBtn">
                            <a class="btn btn-danger mr-2 btn-sm" href="crud/delete.php?klant_id=<?= $arr["klant_id"]; ?>">Delete</a>
                        </td>  
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

    <?php if(count($db->getAvailableKamers()) <= 2): ?>
    <div class="warningKamers">
        <p class="warning">Minder dan 2 kamers zijn beschikbaar.</p>
    </div>
    <?php endif; ?>

    <!-- Kamer overzicht -->
    <table class="overzicht">
        <thead>
            <tr>
                <th scope="col" class="overzichtHead">Kamer ID</th>
                <th scope="col" class="overzichtHead">Kamer Nummer</th>
                <th scope="col" class="overzichtHead">Beschikbaarheid</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach($kamerInfo as $arr): ?>
                    <tr>
                        <td class="overzichtHead"><?php if(!empty($arr['kamer_id'])) echo $arr['kamer_id']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['kamer_nummer'])) echo $arr['kamer_nummer']; ?></td>
                        <td class="overzichtHead"><?php echo empty($arr['beschikbaarheid']) ? 0 : $arr['beschikbaarheid']; ?></td>
                        <td class="overzichtBtn">
                            <a class="btn btn-primary mr-2 btn-sm" href="crud/edit.php?kamer_id=<?= $arr["kamer_id"]; ?>">Edit</a>
                        </td>  
                        <td class="overzichtBtn">
                            <a class="btn btn-danger mr-2 btn-sm" href="crud/delete.php?kamernummer=<?= $arr["kamer_nummer"]; ?>">Delete</a>
                        </td> 
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Klant overzicht -->
    <table class="overzicht">
        <thead>
            <tr>
                <th scope="col" class="overzichtHead">Klant ID</th>
                <th scope="col" class="overzichtHead">Klant Naam</th>
                <th scope="col" class="overzichtHead">Klant Adres</th>
                <th scope="col" class="overzichtHead">Klant Plaats</th>
                <th scope="col" class="overzichtHead">Klant Postcode</th>
                <th scope="col" class="overzichtHead">Klant Telefoonnummer</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach($klantInfo as $arr): ?>
                    <tr>
                        <td class="overzichtHead"><?php if(!empty($arr['klant_id'])) echo $arr['klant_id']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['naam'])) echo $arr['naam']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['adres'])) echo $arr['adres']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['plaats'])) echo $arr['plaats']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['postcode'])) echo $arr['postcode']; ?></td>
                        <td class="overzichtHead"><?php if(!empty($arr['telefoonnummer'])) echo $arr['telefoonnummer']; ?></td>
                        <td class="overzichtBtn">
                            <a class="btn btn-primary mr-2 btn-sm" href="crud/edit.php?klant_id=<?= $arr["klant_id"]; ?>">Edit</a>
                        </td>  
                        <td class="overzichtBtn">
                            <a class="btn btn-danger mr-2 btn-sm" href="crud/delete.php?klant_id=<?= $arr["klant_id"]; ?>">Delete</a>
                        </td>    
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>