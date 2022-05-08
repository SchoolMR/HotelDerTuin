<?php

class database 
{
    private $host;
    private $dbh;
    private $user;
    private $pass;
    private $db;
    private $charset;

    function __construct($host, $user, $pass, $db, $charset = "utf8mb4")
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->charset = $charset;

        try
        {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
            $this->dbh = new PDO($dsn, $this->user, $this->pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 
        }
        catch(PDOException $e)
        {
            die("Unable to connect: " . $e->getMessage());
        }
    }

    public function insertKlant($naam, $adres, $plaats, $postcode, $telnummer)
    {
        $sql = "INSERT INTO klant(klant_id, naam, adres, plaats, postcode, telefoonnummer) 
                VALUES (:id, :naam, :adres, :plaats, :postcode, :telefoonnummer)";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([
            'id'        => NULL,
            'naam'      => $naam,
            'adres'     => $adres,
            'plaats'    => $plaats,
            'postcode'  => $postcode,
            'telefoonnummer' => $telnummer
        ]);

        return $this->dbh->lastInsertId(); // Return de ID van klant
    }

    public function insertReservering($kamer_id, $klant_id, $beginDatum, $eindDatum)
    {
        $sql = "INSERT INTO reservering(reservering_id, kamer_id, klant_id, begin_datum, eind_datum) 
                VALUES (:reservering_id, :kamer_id, :klant_id, :begin_datum, :eind_datum)";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute([
            'reservering_id' => NULL,
            'kamer_id' => $kamer_id,
            'klant_id' => $klant_id,
            'begin_datum' => $beginDatum,
            'eind_datum' => $eindDatum
        ]);

    }

    public function updateKamerBeschikbaarheid($kamernummer, $beschikbaarheid = 0)
    {
        $query = "SELECT * FROM kamer WHERE kamer_nummer = :kamer_nummer";
        $prepare = $this->dbh->prepare($query);
        $prepare->execute([
            'kamer_nummer' => $kamernummer
        ]);

        $result = $prepare->fetch(PDO::FETCH_ASSOC);
        
        if($result['beschikbaarheid'] == 1 && $result['kamer_nummer'] == $kamernummer)
        {
            $update_query = "UPDATE kamer SET beschikbaarheid = :beschikbaarheid WHERE kamer_nummer = :kamer_nummer";
            $update_prep = $this->dbh->prepare($update_query);
            $update_prep->execute([
                'beschikbaarheid' => $beschikbaarheid,
                'kamer_nummer' => $kamernummer
            ]);

            return $result['kamer_id'];
        }
    }

    public function getKamerBeschikbaarheid($kamernummer)
    {
        $query = "SELECT * FROM kamer WHERE kamer_nummer = :kamer_nummer";
        $prepare = $this->dbh->prepare($query);
        $prepare->execute([
            'kamer_nummer' => $kamernummer
        ]);

        $result = $prepare->fetch(PDO::FETCH_ASSOC);
        
        return $result['beschikbaarheid'];
    }

    public function sqlSelect($statement)
    {
        $prepare = $this->dbh->prepare($statement);
        $prepare->execute();

        $rows = $prepare->fetchAll(PDO::FETCH_ASSOC);
            
        return $rows;   
    }

    public function getAvailableKamers()
    {
        return $this->sqlSelect('SELECT beschikbaarheid FROM kamer WHERE beschikbaarheid = 1');
    }

    public function deleteKlant($klant_id)
    {
        $query = $this->dbh->prepare('DELETE klant, reservering FROM klant INNER JOIN reservering WHERE klant.klant_id = reservering.klant_id AND reservering.klant_id = :klant_id');
        $query->execute([
            'klant_id' => $klant_id
        ]);
    }
    
    public function deleteKamer($kamernummer)
    {
        $query = $this->dbh->prepare("DELETE FROM kamer WHERE kamer_nummer = :kamer_nummer");
        $query->execute([
            'kamer_nummer' => $kamernummer
        ]);
    }

    public function updateKamer($kamer_id, $kamernummer, $beschikbaarheid)
    {
        $query = $this->dbh->prepare("UPDATE kamer SET kamer_id = :kamer_id, kamer_nummer = :kamer_nummer, beschikbaarheid = :beschikbaarheid WHERE kamer_id = :kamer_id");
        
        // We zouden kunnen laten kiezen voor hoger of lager dan 1, maar 1 of 0 is simpler omdat dat makkelijker-
        // beschikbaar of niet beschikbaar laat zien
        if($beschikbaarheid > 1)
        {
            $beschikbaarheid = 1;
        }
        else if($beschikbaarheid < 0)
        {
            $beschikbaarheid = 0;
        }
        
        $query->execute([
            'kamer_id' => $kamer_id,
            'kamer_nummer' => $kamernummer,
            'beschikbaarheid' => $beschikbaarheid
        ]);

        header('Location: ../overzicht.php');
    }

    public function updateKlant($klant_id, $naam, $adres, $plaats, $postcode, $telnummer)
    {
        $query = $this->dbh->prepare("UPDATE klant SET naam = :naam, adres = :adres, plaats = :plaats, postcode = :postcode, telefoonnummer = :telefoonnummer WHERE klant_id = :klant_id");
        $query->execute([
            'klant_id' => $klant_id,
            'naam' => $naam,
            'adres' => $adres,
            'plaats' => $plaats,
            'postcode' => $postcode,
            'telefoonnummer' => $telnummer
        ]);

        header('Location: ../overzicht.php');
    }

    public function updateReservering($reservering_id, $beginDatum, $eindDatum)
    {
        $query = $this->dbh->prepare("UPDATE reservering SET begin_datum = :begin_datum, eind_datum = :eind_datum WHERE reservering_id = :reservering_id");
        $query->execute([
            'reservering_id' => $reservering_id,
            'begin_datum' => $beginDatum,
            'eind_datum' => $eindDatum
        ]);

        header('Location: ../overzicht.php');
    }

    public function loginLogic($username, $password)
    {
        $query = "SELECT * FROM medewerker WHERE medewerker_username = :medewerker_username";
        $prepare = $this->dbh->prepare($query);
        $prepare->execute([
            'medewerker_username' => $username
        ]);

        $result = $prepare->fetch(PDO::FETCH_ASSOC);

        if($result['medewerker_username'] == $username && $result['medewerker_pass'] == $password)
        {
            session_start();
            $_SESSION['username'] = $username;
            header('location: index.php');
        }
    }

}

?>