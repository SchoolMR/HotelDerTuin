<?php
    require '../backend/database.php';

    $db = new database('localhost', 'root', '', 'hoteldertuin');
    
    if(isset($_GET['klant_id']))
    {
        $db->deleteKlant($_GET['klant_id']);
    }
    else if(isset($_GET['kamernummer']))
    {
        $db->deleteKamer($_GET['kamernummer']);
    }

    header('Location: ../overzicht.php');

?>