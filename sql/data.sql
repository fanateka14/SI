-- Insertion de departements
INSERT INTO Dept (nomDept, mdp) VALUES 
('Finance', 'mdp123'),
('Ressources Humaines', 'mdp456'),
('Informatique', 'mdp789'),
('Marketing', 'mdp101'),
('Logistique', 'mdp102');

-- Insertion des relations de droits entre departements
INSERT INTO Droit (idDeptPere, idDeptFils) VALUES 
(1, 2),  -- Finance peut acceder a Ressources Humaines
(1, 3),  -- Finance peut acceder a Informatique
(1, 4),  -- Finance peut acceder a Marketing
(1, 5),  -- Finance peut acceder a Logistique
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);  -- Finance peut acceder a Logistique
  -- Finance peut acceder a Logistique
  -- Finance peut acceder a Logistique
  -- Finance peut acceder a Logistique
  -- Finance peut acceder a Logistique

-- (2, 3),  -- Ressources Humaines peut acceder a Informatique
-- (2, 4),  -- Ressources Humaines peut acceder a Marketing
-- (3, 5);  -- Informatique peut acceder a Logistique

-- Insertion de categories (recettes et depenses)
INSERT INTO Categorie (nomCategorie, recetteOuDepense) VALUES 
('Salaires', 0),  -- Depense
('Achats', 0),    -- Depense
('Ventes', 1),    -- Recette
('Publicite', 1), -- Recette
('Investissements', 0),  -- Depense
('Entretien', 0), -- Depense
('CRM', 0);  -- Depense

-- Insertion de types associes a chaque categorie
INSERT INTO Type (idCategorie, nomType) VALUES 
(1, 'Remuneration'),      -- Type pour "Salaires"
(2, 'Fournitures'),       -- Type pour "Achats"
(3, 'Produits vendus'),   -- Type pour "Ventes"
(4, 'Campagnes pub'),     -- Type pour "Publicite"
(5, 'Achat materiel'),    -- Type pour "Investissements"
(6, 'Maintenance'),       -- Type pour "Entretien"
(7,'Reaction CRM');  -- Depense

-- Departement Finance
INSERT INTO Valeur (nomRubrique, idType, idDept, previsionOuRealisation, montant, date, validation) VALUES 
-- Paiement des salaires
('Paiement des salaires', 1, 1, 0, 5200000, '2025-03-01', 0),  -- Prevision
('Paiement des salaires', 1, 1, 1, 5000000, '2025-03-01', 1),  -- Realisation

-- Achat de fournitures
('Achat de fournitures', 2, 1, 0, 150000, '2025-03-02', 0),    -- Prevision
('Achat de fournitures', 2, 1, 1, 130000, '2025-03-03', 1),    -- Realisation

-- Investissements en equipements
('Investissements en equipements', 5, 1, 0, 2000000, '2025-03-03', 0),  -- Prevision
('Investissements en equipements', 5, 1, 1, 1800000, '2025-03-10', 1),  -- Realisation

-- Recette de vente de services
('Recette de vente de services', 3, 1, 0, 8500000, '2025-03-04', 0),  -- Prevision
('Recette de vente de services', 3, 1, 1, 8000000, '2025-03-05', 1);  -- Realisation

-- Departement Ressources Humaines
INSERT INTO Valeur (nomRubrique, idType, idDept, previsionOuRealisation, montant, date, validation) VALUES 
-- Salaire employes
('Salaire employes', 1, 2, 0, 4200000, '2025-03-01', 0),  -- Prevision
('Salaire employes', 1, 2, 1, 4000000, '2025-03-01', 1),  -- Realisation

-- Achat de fournitures de bureau
('Achat de fournitures de bureau', 2, 2, 0, 100000, '2025-03-04', 0),  -- Prevision
('Achat de fournitures de bureau', 2, 2, 1, 95000, '2025-03-06', 1),   -- Realisation

-- Recette de formation
('Recette de formation', 3, 2, 0, 2100000, '2025-03-07', 0),  -- Prevision
('Recette de formation', 3, 2, 1, 2000000, '2025-03-08', 1);  -- Realisation

-- Departement Informatique
INSERT INTO Valeur (nomRubrique, idType, idDept, previsionOuRealisation, montant, date, validation) VALUES 
-- Salaire du personnel informatique
('Salaire du personnel informatique', 1, 3, 0, 6100000, '2025-03-01', 0),  -- Prevision
('Salaire du personnel informatique', 1, 3, 1, 6000000, '2025-03-01', 1),  -- Realisation

-- Achat de materiel informatique
('Achat de materiel informatique', 2, 3, 0, 1200000, '2025-03-02', 0),  -- Prevision
('Achat de materiel informatique', 2, 3, 1, 1150000, '2025-03-05', 1),  -- Realisation

-- Recette vente de logiciels
('Recette vente de logiciels', 3, 3, 0, 5500000, '2025-03-06', 0),  -- Prevision
('Recette vente de logiciels', 3, 3, 1, 5000000, '2025-03-07', 1);  -- Realisation

-- Departement Marketing
INSERT INTO Valeur (nomRubrique, idType, idDept, previsionOuRealisation, montant, date, validation) VALUES 
-- Publicite sur reseaux sociaux
('Publicite sur reseaux sociaux', 4, 4, 0, 300000, '2025-03-05', 0),  -- Prevision
('Publicite sur reseaux sociaux', 4, 4, 1, 280000, '2025-03-08', 1),  -- Realisation

-- Recette publicite en ligne
('Recette publicite en ligne', 3, 4, 0, 2100000, '2025-03-09', 0),  -- Prevision
('Recette publicite en ligne', 3, 4, 1, 2000000, '2025-03-10', 1);  -- Realisation

-- Departement Logistique
INSERT INTO Valeur (nomRubrique, idType, idDept, previsionOuRealisation, montant, date, validation) VALUES 
-- Investissement en materiel logistique
('Investissement en materiel logistique', 5, 5, 0, 1000000, '2025-03-02', 0),  -- Prevision
('Investissement en materiel logistique', 5, 5, 1, 950000, '2025-03-09', 1),  -- Realisation

-- Entretien des equipements
('Entretien des equipements', 6, 5, 0, 500000, '2025-03-06', 0),  -- Prevision
('Entretien des equipements', 6, 5, 1, 480000, '2025-03-08', 1),  -- Realisation

-- Recette livraison de produits
('Recette livraison de produits', 3, 5, 0, 4200000, '2025-03-09', 0),  -- Prevision
('Recette livraison de produits', 3, 5, 1, 4000000, '2025-03-10', 1);  -- Realisation

-- Insertion des soldes initiaux pour les departements
INSERT INTO soldeInitial (idDept, montant, dateInsertion) VALUES
(1, 1000000.00, '2025-01-01'),  -- Solde initial pour le departement Finance
(2, 500000.00, '2025-01-01'),   -- Solde initial pour le departement Ressources Humaines
(3, 1500000.00, '2025-01-01');  -- Solde initial pour le departement Informatique

INSERT INTO produit (nomProduit, prix, stock) VALUES
('Ballon de football', 25000.00, 50),
('Raquette de tennis', 85000.00, 20),
('Tapis de yoga', 30000.00, 40),
('Halteres 10kg', 40000.00, 30),
('Chaussures de course', 120000.00, 25),
('Gants de boxe', 45000.00, 15),
('Casque de velo', 60000.00, 10),
('Maillot de sport', 20000.00, 60),
('Sac de sport', 35000.00, 35),
('Filet de volley', 50000.00, 12);

INSERT INTO client (nomClient) VALUES
('Andry Rakoto'),
('Fanja Rasoanaivo'),
('Jean-Claude Randria'),
('Mialy Andriamanana'),
('Tojo Rabe'),
('Elena Rasoa'),
('Niry Rafalimanana'),
('Patrick Rakotomanga');

-- INSERT INTO vente (idProduit, idClient, dateVente, quantite) VALUES
-- (1, 1, '2025-04-01', 2),
-- (3, 2, '2025-04-02', 1),
-- (5, 3, '2025-04-05', 1),
-- (4, 1, '2025-04-06', 2),
-- (2, 4, '2025-04-07', 1),
-- (6, 5, '2025-04-08', 1),
-- (7, 6, '2025-04-09', 1),
-- (8, 2, '2025-04-10', 3),
-- (9, 7, '2025-04-11', 2),
-- (10, 8, '2025-04-12', 1);

INSERT INTO Crm (label) VALUES
('Email de bienvenue avec reduction Popup de chat en direct'),
('Campagne Google Ads Facebook ciblee Partenariat avec des influenceurs'),
('Sondages questions interactives Codes promo reserves aux followers'),
('Retargeting sur reseaux sociaux Placement produit en magasin lie a la pub'),
('Video comparative Essai gratuit echantillon'),
('Paiement en 3x sans frais Notification push Votre panier expire'),
('Flyers avec QR code vers offre offline Evenement eclair degustation demo'),
('Questionnaire post evenement Goodies avec lien vers site'),
('Email recap avec fiche produit Envoi dechantillon gratuit'),
('Envoyer un parcours personnalise Preparez vous a levenement Offrir un diagnostic materiel gratuit sur place'),
('Envoyer une notification geolocalisee avec un code vitrine exclusive Afficher un QR code pour scanner les avis clients'),
('Lui proposer un contenu expert ex 5 erreurs a eviter avec vos crampons Inviter a un live QA avec un pro'),
('Envoyer les creneaux de forte affluence a eviter Proposer un rendez vous en magasin avec un expert'),
('Faire apparaitre une annonce Google avec un guide dachat localise Proposer un essai en partenariat avec un club local'),
('Envoyer un programme de parrainage avec recompenses ex equipement offert Offrir un duo de places pour un match partenaire'),
('Debloquer un badge Nouveau membre avec avantage immediat Proposer un jeu concours pour gagner un coaching'),
('Envoyer des recommandations personnalisees par sport Programmer un rappel pour essai en magasin'),
('Envoyer un message prive avec une demo exclusive Proposer un essai du produit en boutique avec cadeau'),
('Activer une alerte Stock faible Envoyer un tutoriel dutilisation du produit'),
('Envoyer un itineraire personnalise Declencher une offre Passage en magasin ex 15 pourcent'),
('Proposer des tarifs groupe Offrir un audit gratuit du materiel de lequipe'),
('Debloquer des avis clients verifies Proposer une reduction immediate sur le produit scanne'),
('Envoyer un guide sportif saisonnier ex Preparer la saison de volley Donner acces a des ventes privees'),
('Proposer une version longue avec un expert Inviter a une session de test en reel'),
('Repondre personnellement avec une offre de remerciement Inviter a tester les nouveautes en avant premiere'),
('Preparer une surprise en magasin cadeau decoration Offrir un credit valable 1 semaine'),
('Mettre en place un parcours de decouverte de la marque Programmer un appel de suivi meme sans gain'),
('Lui envoyer un role Ambassadeur avec missions Organiser des meetups sportifs prives'),
('Envoyer une fiche produit enrichie video avis Proposer un comparatif en temps reel avec dautres modeles');




INSERT INTO vente (idProduit, idClient, dateVente, quantite) VALUES
(1, 1, '2025-04-01', 2),
(3, 2, '2025-04-02', 1),
(5, 3, '2025-04-05', 1),
(4, 1, '2025-04-06', 2),
(2, 4, '2025-04-07', 1),
(6, 5, '2025-04-08', 1),
(7, 6, '2025-04-09', 1),
(8, 2, '2025-04-10', 3);
update dept set mdp='1234'; 
alter table dept add column budget decimal(15,2)  default 0;

insert into dept (nomDept, mdp, budget) values
('Commerciale', '1234', 1000000.00);
update dept set budget = 1000000 where idDept= 1; 
-- ...existing code...
CREATE TABLE Crm_vrai (
    idCrm INT PRIMARY KEY AUTO_INCREMENT,
    label VARCHAR(500) NOT NULL,
    action VARCHAR(500) NOT NULL,
    idDept INT NOT NULL,
    valeur DECIMAL(15,2) NOT NULL,
    dateCrm DATE NOT NULL
);

CREATE TABLE Crm_temp (
    idCrm INT PRIMARY KEY AUTO_INCREMENT,
    label VARCHAR(500) NOT NULL,
    action VARCHAR(500) NOT NULL,
    idDept INT NOT NULL,
    valeur DECIMAL(15,2) NOT NULL,
    dateCrm DATE NOT NULL
);
create table budgetCRM(
  idBudgetCRM int primary key auto_increment,
  idDept int not null,  
  montant decimal(15,2) not null
);
insert into budgetCRM (idDept, montant) values
(1, 500000.00), 
(2, 300000.00), 
(3, 400000.00),  
(4, 200000.00),  
(5, 250000.00),
(6, 150000.00);

CREATE TABLE Ticket (
    idTicket INT PRIMARY KEY AUTO_INCREMENT,
    idClient INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES client(idClient)
);
-- Donnees de test pour Ticket et Historique_Ticket

-- Tickets pour 3 clients differents
INSERT INTO Ticket (idClient) VALUES (1); -- idTicket = 1
INSERT INTO Ticket (idClient) VALUES (2); -- idTicket = 2
INSERT INTO Ticket (idClient) VALUES (3); -- idTicket = 3

-- Historique pour chaque ticket avec differentes priorites et statuts
INSERT INTO Historique_Ticket (idTicket, instruction, date, priorite, statut, action) VALUES
(1, 'Probleme de connexion a la plateforme', '2025-04-10 09:00:00', 'haute', 'ouvert', 'creation'),
(1, 'Ticket pris en charge par le support', '2025-04-10 10:00:00', 'haute', 'ouvert', 'modification'),
(1, 'Probleme resolu, ticket ferme', '2025-04-10 11:00:00', 'haute', 'ferme', 'fermeture'),

(2, 'Demande de mise a jour de profil', '2025-04-11 14:30:00', 'moyenne', 'ouvert', 'creation'),
(2, 'Mise a jour effectuee', '2025-04-11 15:00:00', 'moyenne', 'ferme', 'fermeture'),

(3, 'Question sur la facturation', '2025-04-12 08:20:00', 'basse', 'ouvert', 'creation');
CREATE TABLE Historique_Ticket (
    idHistorique INT PRIMARY KEY AUTO_INCREMENT,
    idTicket INT NOT NULL,
    instruction TEXT NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    priorite ENUM('basse', 'moyenne', 'haute') NOT NULL,
    statut ENUM('ouvert', 'ferme') NOT NULL,
    action VARCHAR(50) NOT NULL, -- ex: 'creation', 'modification', 'fermeture'
    FOREIGN KEY (idTicket) REFERENCES Ticket(idTicket)
); 

-- Insertion de ventes pour differents mois
-- INSERT INTO vente (idProduit, idClient, dateVente, quantite) VALUES
-- (1, 1, '2025-01-15', 2),  -- Janvier
-- (3, 2, '2025-01-20', 1),
-- (5, 3, '2025-02-05', 1),  -- Fevrier
-- (4, 1, '2025-02-10', 2),
-- (2, 4, '2025-03-07', 1),  -- Mars
-- (6, 5, '2025-03-08', 1),
-- (7, 6, '2025-04-09', 1),  -- Avril
-- (8, 2, '2025-04-10', 3),
-- (9, 3, '2025-05-12', 1),  -- Mai
-- (10, 4, '2025-05-15', 1),
-- (1, 5, '2025-06-18', 2),  -- Juin
-- (2, 6, '2025-06-20', 1),
-- (3, 7, '2025-07-22', 1),  -- Juillet
-- (4, 8, '2025-07-25', 2),
-- (5, 1, '2025-08-15', 1),  -- Août
-- (6, 2, '2025-08-18', 2),
-- (7, 3, '2025-09-10', 1),  -- Septembre
-- (8, 4, '2025-09-12', 1),
-- (9, 5, '2025-10-05', 2),  -- Octobre
-- (10, 6, '2025-10-09', 1),
-- (1, 7, '2025-11-11', 1),  -- Novembre
-- (2, 8, '2025-11-15', 3),
-- (3, 1, '2025-12-20', 1),  -- Decembre
-- (4, 2, '2025-12-25', 2);

