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


-- Table pour les avis sur les tickets (commentaire + note étoile)

drop table ticket_review;
CREATE TABLE IF NOT EXISTS ticket_review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    commentaire TEXT,
    nb_etoile INT CHECK (nb_etoile BETWEEN 1 AND 5),
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(idTicket)
);

-- Pour insérer des exemples :
-- INSERT INTO ticket_review (id_ticket, commentaire, nb_etoile) VALUES (1, 'Très bon support', 5);
