CREATE TABLE assignation_ticket (
    idAsignation INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT NOT NULL,
    montantPrevu DOUBLE NOT NULL,
    duree DOUBLE NOT NULL,
    dureeReel DOUBLE DEFAULT 0
);



-- Table pour les avis sur les tickets (commentaire + note étoile)

drop table ticket_review;
CREATE TABLE IF NOT EXISTS ticket_review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    commentaire TEXT,
    nb_etoile INT CHECK (nb_etoile BETWEEN 1 AND 5),
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO Dept (nomDept, mdp, budget) VALUES ('client', '1234', 0.00);
