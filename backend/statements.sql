Create DATABASE hoteldertuin;
USE hoteldertuin;

CREATE TABLE kamer(
    kamer_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    kamer_nummer INT NOT NULL
);

CREATE TABLE klant(
    klant_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    naam VARCHAR(255) NOT NULL,
    adres VARCHAR(255) NOT NULL,
    plaats VARCHAR(255) NOT NULL,
    postcode VARCHAR(20) NOT NULL,
    telefoonnummer VARCHAR(20) NOT NULL
);

CREATE TABLE reservering(
    reservering_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    kamer_id INT NOT NULL,
    klant_id INT NOT NULL,
    begin_datum DATE NOT NULL,
    eind_datum DATE NOT NULL,
    reservering_nummer INT NOT NULL,

    FOREIGN KEY(kamer_id) REFERENCES kamer(kamer_id),
    FOREIGN KEY(klant_id) REFERENCES klant(klant_id)
);

CREATE TABLE overzicht(
    overzicht_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    reservering_id INT NOT NULL,

    FOREIGN KEY(reservering_id) REFERENCES reservering(reservering_id)
);

CREATE TABLE medewerker(
    medewerker_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    medewerker_username VARCHAR(255) NOT NULL,
    medewerker_pass VARCHAR(255) NOT NULL
);

INSERT INTO medewerker(medewerker_username, medewerker_pass) VALUES ('admin', 'root');
INSERT INTO medewerker(medewerker_username, medewerker_pass) VALUES ('mike', '123');
ALTER TABLE kamer ADD beschikbaarheid BIT NOT NULL;
ALTER TABLE kamer MODIFY COLUMN beschikbaarheid TINYINT(1) NOT NULL; 
ALTER TABLE reservering DROP COLUMN reservering_nummer;

INSERT INTO kamer (kamer_id, kamer_nummer, beschikbaarheid)
VALUES (NULL, 1, 1), (NULL, 2, 1), (NULL, 3, 1), (NULL, 4, 1), (NULL, 5, 1), (NULL, 6, 1), (NULL, 7, 1), (NULL, 8, 1), (NULL, 9, 1), (NULL, 10, 1) 
