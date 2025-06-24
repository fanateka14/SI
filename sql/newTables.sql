CREATE TABLE assignation_ticket (
    idAsignation INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT NOT NULL,
    montantPrevu DOUBLE NOT NULL,
    duree DOUBLE NOT NULL,
    dureeReel DOUBLE DEFAULT 0
);
