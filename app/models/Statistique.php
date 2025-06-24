<?php

namespace app\models;

use Flight;

class Statistique {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Recupere les 5 meilleurs produits vendus
     * @return array Tableau des meilleurs produits avec leur quantite totale vendue
     */
    public function getBestProduct() {
        $query = "
            SELECT p.idProduit, p.nomProduit, SUM(v.quantite) as total_vendu
            FROM vente v
            JOIN produit p ON v.idProduit = p.idProduit
            GROUP BY v.idProduit
            ORDER BY total_vendu DESC
            LIMIT 5
        ";

        $result = $this->db->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Recupere les clients les plus fideles (plus gros acheteurs)
     * @param int $limit Nombre de clients a retourner
     * @return array
     */
    public function getTopCustomers() {
        $query = "
            SELECT c.idClient, c.nomClient, SUM(v.quantite * p.prix) as total_achats
            FROM client c
            JOIN vente v ON c.idClient = v.idClient
            JOIN produit p ON v.idProduit = p.idProduit
            GROUP BY c.idClient
            ORDER BY total_achats DESC
            LIMIT 5
        ";
        
        $result = $this->db->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Recupere les CRM qui ont le plus fonctionne et augmente les ventes
     * Note: Cette implementation suppose que vous avez une logique pour lier les CRM aux ventes
     * (par exemple via une table de liaison). Comme cette table n'est pas definie dans votre schema,
     * je fais une implementation de base qui pourrait necessiter des ajustements.
     * @return array Tableau des CRM les plus efficaces
     */
    public function getBestCrm() {
        // Implementation de base - a adapter selon votre logique metier
        $query = "
            SELECT c.idCrm, c.label, COUNT(v.idVente) as ventes_associees
            FROM Crm c
            LEFT JOIN vente v ON /* Ici devrait être votre condition de liaison entre CRM et ventes */
            GROUP BY c.idCrm
            ORDER BY ventes_associees DESC
        ";

        $result = $this->db->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Version alternative de getBestCrm si vous avez une table de liaison entre CRM et clients
     * (supposons que vous avez une table client_crm avec idClient et idCrm)
     */
    public function getBestCrmAlternative() {
        $query = "
            SELECT crm.idCrm, crm.label, COUNT(v.idVente) as ventes_associees
            FROM Crm crm
            JOIN client_crm cc ON crm.idCrm = cc.idCrm
            JOIN vente v ON cc.idClient = v.idClient
            GROUP BY crm.idCrm
            ORDER BY ventes_associees DESC
        ";

        $result = $this->db->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Version amelioree avec gestion des mois sans vente
    public function getSalesByMonth($year = null) {
        if ($year === null) {
            $year = date('Y');
        }

        $query = "SELECT 
                        MONTH(dateVente) as mois,
                        SUM(quantite) as total_ventes
                    FROM vente
                    WHERE YEAR(dateVente) = :year
                    GROUP BY mois
                    ORDER BY mois
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':year' => $year
        ]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Remplir les mois manquants avec 0
        $allMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $allMonths[$i] = ['mois' => $i, 'total_ventes' => 0];
        }

        foreach ($result as $row) {
            $allMonths[$row['mois']] = $row;
        }

        return array_values($allMonths);
    }

    function getChiffreAffaire($year): float {
        $sql = "
            SELECT SUM(p.prix * v.quantite) AS chiffre_affaire
            FROM vente v
            JOIN produit p ON v.idProduit = p.idProduit
            WHERE YEAR(v.dateVente) = :year
        ";
    
        $stmt = Flight::db()->prepare($sql);
        $stmt->execute([
            'year' => $year
        ]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        return $result['chiffre_affaire'] ?? 0.0;
    }
    
}
?>