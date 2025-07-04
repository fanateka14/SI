CREATE TABLE discussion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idTier INT NOT NULL,
    message TEXT NOT NULL,
    reponse TEXT,
    dateHeure DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE Service (
    idService INT PRIMARY KEY AUTO_INCREMENT,
    idTicket INT,
    note INT,
    commentaire VARCHAR(255),
    FOREIGN KEY (idTicket) REFERENCES Ticket(idTicket)
);
CREATE TABLE budgetCRM (
    id INT AUTO_INCREMENT PRIMARY KEY,
    budget INT NOT NULL
);
INSERT INTO budgetCRM (idDept, montant) VALUES (1, 5000);