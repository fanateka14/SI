CREATE TABLE assignation_ticket (
    idAsignation INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT NOT NULL,
    montantPrevu DOUBLE NOT NULL,
    duree DOUBLE NOT NULL,
    dureeReel DOUBLE DEFAULT 0
);


INSERT INTO assignation_ticket (idTicket, montantPrevu, duree, dureeReel) VALUES
(1, 10000, 2, 2.5),
(2, 8000, 1, 1),
(3, 12000, 3, 2.8);
