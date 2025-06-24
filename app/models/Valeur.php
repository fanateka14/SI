<?php
namespace app\models;
use Flight;


class Valeur {
    private $idValeur;
    private $nomRubrique;
    private $idType;
    private $previsionOuRealisation;
    private $montant;
    private $date;
    private $validation;
    private $idDept;
    private $conn;

    // Constructeur qui prend des valeurs pour les attributs
    public function __construct($idValeur, $nomRubrique, $idType, $previsionOuRealisation, $montant, $date, $validation, $idDept) {
        $this->setIdValeur($idValeur);
        $this->setNomRubrique($nomRubrique);
        $this->setIdType($idType);
        $this->setPrevisionOuRealisation($previsionOuRealisation);
        $this->setMontant($montant);
        $this->setDate($date);
        $this->setValidation($validation);
        $this->setIdDept($idDept);
        $this->conn = Flight::db();  // Connexion a la base de donnees
    }

    // Getters et Setters
    public function getIdValeur() {
        return $this->idValeur;
    }

    public function setIdValeur($idValeur) {
        $this->idValeur = $idValeur;
    }
    

    public function getNomRubrique() {
        return $this->nomRubrique;
    }

    public function setNomRubrique($nomRubrique) {
        $this->nomRubrique = $nomRubrique;
    }

    public function getIdType() {
        return $this->idType;
    }

    public function setIdType($idType) {
        $this->idType = $idType;
    }

    public function getPrevisionOuRealisation() {
        return $this->previsionOuRealisation;
    }

    public function setPrevisionOuRealisation($previsionOuRealisation) {
        $this->previsionOuRealisation = $previsionOuRealisation;
    }

    public function getMontant() {
        return $this->montant;
    }

    public function setMontant($montant) {
        $this->montant = $montant;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getValidation() {
        return $this->validation;
    }

    public function setValidation($validation) {
        $this->validation = $validation;
    }

    public function getIdDept() {
        return $this->idDept;
    }

    public function setIdDept($idDept) {
        $this->idDept = $idDept;
    }

    // Methode pour sauvegarder ou inserer la valeur dans la base de donnees
    public function insert() {
        $sql = "INSERT INTO Valeur (nomRubrique, idType, previsionOuRealisation, montant, date, validation, idDept) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $this->getNomRubrique(), 
            $this->getIdType(), 
            $this->getPrevisionOuRealisation(), 
            $this->getMontant(), 
            $this->getDate(), 
            $this->getValidation(),
            $this->getIdDept()
        ]);
    }

    public static function gestionPrevisionRealisation($previsionOuRealisation) {
    // Convertir en minuscule et supprimer les espaces superflus
    $previsionOuRealisation = strtolower(trim($previsionOuRealisation));
    // Convertir les caracteres accentues en leur equivalent non accentue
    $previsionOuRealisation = iconv('UTF-8', 'ASCII//TRANSLIT', $previsionOuRealisation);

    if ($previsionOuRealisation === "prevision" || $previsionOuRealisation === "1") {
        return 1;
    } elseif ($previsionOuRealisation === "realisation" || $previsionOuRealisation === "0") {
        return 0;
    } else {
        return null;
    }
}


    public static function getListeValeurFromCsv($filePath = "") {
        $valeurs = [];
        
        if (($fileCsv = fopen($filePath, "r")) !== false) {
            $headers = fgetcsv($fileCsv, 1000, ";");

            if ($headers === false) {
                echo "Erreur : impossible de lire l'entête du fichier.";
                fclose($fileCsv);
                return [];
            }

            while (($data = fgetcsv($fileCsv, 1000, ";")) !== false) {
                $row = array_combine($headers, $data);

                foreach ($row as $key => $value) {
                    $row[$key] = trim($value, "'\"");
                }

                $previsionOuRealisationValue = Valeur::gestionPrevisionRealisation($row['previsionOuRealisation'] ?? '');

                // 🔍 Conversion nomType → idType
                $type = Type::getTypeByName($row['nomType'] ?? '');
                if (!$type) {
                    echo "⚠️ Erreur : Type inconnu ({$row['nomType']}). Verifiez la base de donnees.\n";
                    continue; // Passer a la ligne suivante
                }
                $row['idType'] = $type->getIdType();

                // 🔍 Conversion nomDept → idDept
                $departement = Departement::getDepartementByName($row['nomDept'] ?? '');
                if (!$departement) {
                    echo "⚠️ Erreur : Departement inconnu ({$row['nomDept']}). Verifiez la base de donnees.\n";
                    continue; // Passer a la ligne suivante
                }
                $row['idDept'] = $departement->getIdDept();

                // 🔍 Verification avant insertion
                if (empty($row['idType']) || empty($row['idDept'])) {
                    echo "⛔ Erreur : idType ou idDept manquant pour la ligne : " . json_encode($row) . "\n";
                    continue;
                }

                // Creation de l'objet Valeur
                $valeur = new Valeur(
                    $row['idValeur'] ?? null,
                    $row['nomRubrique'] ?? null,
                    $row['idType'],
                    $previsionOuRealisationValue,
                    $row['montant'] ?? null,
                    $row['date'] ?? null,
                    0,
                    $row['idDept']
                );
                $valeurs[] = $valeur;

                // Sauvegarde en base de donnees
                $valeur->insert();
            }

            fclose($fileCsv);
        } else {
            echo "Erreur : impossible d'ouvrir le fichier.";
        }

        return $valeurs;
    }


    
    
}
?>
