<?php

class Model {

    private $bd;
    private static $instance = null;

    private function __construct() {
        try {
            $this->bd = new PDO("mysql:host=localhost;dbname=database_board_games", "root", "");
            $this->bd->query("SET NAMES 'utf8'");
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getModel() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->bd;
    }

    
    public function getHomeGames() {
        $requete = $this->bd->prepare("SELECT * FROM Jeux LIMIT 30");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function recherche() {
        if (isset($_POST['s']) && !empty($_POST['s'])) {
            $search = htmlspecialchars($_POST['s']);
            
            
            $query = $this->bd->prepare('SELECT * FROM Jeux WHERE titre LIKE :search ORDER BY jeu_id DESC');
            $query->execute(['search' => '%' . $search . '%']); // Exécution avec un paramètre sécurisé
            
            return $query->fetchall(PDO::FETCH_ASSOC);
        }
        
        return false;
    }
    public function rechercheb() {
        if (isset($_POST['s']) && !empty($_POST['s'])) {
            $search = htmlspecialchars($_POST['s']);
            $results = [];

            // Requête par titre
            $queryTitle = $this->bd->prepare('SELECT * FROM Jeux WHERE titre LIKE :search ORDER BY jeu_id DESC');
            $queryTitle->execute(['search' => '%' . $search . '%']);
            $resultsTitle = $queryTitle->fetchAll(PDO::FETCH_ASSOC);

            // Requête par éditeur
            $queryEditeur = $this->bd->prepare('
                SELECT j.* FROM Jeux j
                JOIN JeuEditeur je ON j.jeu_id = je.jeu_id
                JOIN Editeurs e ON je.editeur_id = e.editeur_id
                WHERE e.nom LIKE :search
                ORDER BY j.jeu_id DESC
            ');
            $queryEditeur->execute(['search' => '%' . $search . '%']);
            $resultsEditeur = $queryEditeur->fetchAll(PDO::FETCH_ASSOC);

            // Requête par auteur
            $queryAuteur = $this->bd->prepare('
                SELECT j.* FROM Jeux j
                JOIN JeuAuteur ja ON j.jeu_id = ja.jeu_id
                JOIN Auteurs a ON ja.auteur_id = a.auteur_id
                WHERE a.nom LIKE :search
                ORDER BY j.jeu_id DESC
            ');
            $queryAuteur->execute(['search' => '%' . $search . '%']);
            $resultsAuteur = $queryAuteur->fetchAll(PDO::FETCH_ASSOC);

            // Requête par mécanisme
            $queryMecanisme = $this->bd->prepare('
                SELECT j.* FROM Jeux j
                JOIN JeuMecanisme jm ON j.jeu_id = jm.jeu_id
                JOIN Mecanismes m ON jm.mecanisme_id = m.mecanisme_id
                WHERE m.nom LIKE :search
                ORDER BY j.jeu_id DESC
            ');
            $queryMecanisme->execute(['search' => '%' . $search . '%']);
            $resultsMecanisme = $queryMecanisme->fetchAll(PDO::FETCH_ASSOC);

            // Recherche par date uniquement si c'est un nombre de 4 chiffres
            $resultsDate = [];
            if (strlen($search) == 4 && is_numeric($search)) {
                $queryDate = $this->bd->prepare('SELECT * FROM Jeux WHERE date_parution_debut = :search ORDER BY jeu_id DESC');
                $queryDate->execute(['search' => $search]);
                $resultsDate = $queryDate->fetchAll(PDO::FETCH_ASSOC);
            }

            // Fusionner tous les résultats
            $allResults = array_merge($resultsTitle, $resultsEditeur, $resultsAuteur, $resultsMecanisme, $resultsDate);

            // Supprimer les doublons par jeu_id
            $uniqueResults = [];
            foreach ($allResults as $item) {
                $uniqueResults[$item['jeu_id']] = $item;
            }

            return array_values($uniqueResults);
        }

        return false;
    }


        // Requête pour chercher un jeu par son titre
        public function getJeuByTitre($titre) {
            $requete = $this->bd->prepare("SELECT * FROM Jeux WHERE titre = :titre");
            $requete->bindParam(':titre', $titre);
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        }
    
    
        public function getEditeurByTitre($titre) {
            $requete = $this->bd->prepare(
                "SELECT Editeurs.nom FROM Editeurs
                 JOIN JeuEditeur ON Editeurs.editeur_id = JeuEditeur.editeur_id
                 JOIN Jeux ON JeuEditeur.jeu_id = Jeux.jeu_id
                 WHERE Jeux.titre = :titre"
            );
            $requete->bindValue(':titre', $titre);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC); 
        }
        public function getAuteursByTitre($titre){
            $requete = $this->bd->prepare(
                "SELECT Auteurs.nom FROM Auteurs
                 JOIN JeuAuteur ON Auteurs.auteur_id = JeuAuteur.auteur_id
                 JOIN Jeux ON JeuAuteur.jeu_id = Jeux.jeu_id
                 WHERE Jeux.titre = :titre"
            );
            $requete->bindValue(':titre', $titre);
            $requete->execute();
            return $requete->fetch(PDO::FETCH_ASSOC); 
        }
    
       public function getJeux($page){
        $offset = ($page - 1) * 30;
        $requete = $this->bd->prepare("SELECT titre FROM Jeux LIMIT 30 OFFSET :offset");
        $requete->bindParam( ":offset",$offset, PDO::PARAM_INT);
        $requete->execute();
        return $requete->fetchall(PDO::FETCH_ASSOC);
        

    
       
    
        }

        public function getCategorie(){

        $requete= $this->bd->prepare("SELECT DISTINCT nom FROM Mecanismes  ORDER BY nom ASC");
        $requete->execute();
        return $requete->fetchall(PDO::FETCH_ASSOC);

    }
        public function getMecanismesByJeu($jeuNom) {
            $requete = $this->bd->prepare(
                "SELECT Mecanismes.* FROM Mecanismes
                 JOIN JeuMecanisme ON Mecanismes.mecanisme_id = JeuMecanisme.mecanisme_id
                 JOIN Jeux ON JeuMecanisme.jeu_id = Jeux.jeu_id
                 WHERE Jeux.titre = :jeuNom"
            );
            $requete->bindValue(':jeuNom', trim($jeuNom));
            $requete->execute();
            return $requete->fetchAll(PDO::FETCH_ASSOC);
        }
  

        public function getJeuByMecanismes($mecanismesNom) { 
        $requete = $this->bd->prepare(
            "SELECT titre FROM Jeux
             JOIN JeuMecanisme ON Jeux.Jeu_id = JeuMecanisme.jeu_id
             JOIN Mecanismes ON JeuMecanisme.mecanisme_id = Mecanismes.mecanisme_id
             WHERE Mecanismes.nom = :mecanismesNom"
        );
        $requete->bindValue('mecanismesNom', $mecanismesNom);
        $requete->execute();
        return $requete->fetchall(PDO::FETCH_ASSOC);
    }
        public function countJeuxParMecanisme() {
        $requete = $this->bd->prepare(
            "SELECT M.nom AS mecanisme, COUNT(J.Jeu_id) AS nombre_jeux FROM Mecanismes M LEFT JOIN JeuMecanisme JM ON M.mecanisme_id = JM.mecanisme_id LEFT JOIN Jeux J ON JM.jeu_id = J.Jeu_id GROUP BY M.nom "
        );
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC); // Retourne toutes les lignes
    }

    public function 
    getBoiteIdParTitre($titre) {

        $requete= $this->bd->prepare(" SELECT boite_id FROM Boites JOIN Jeux USING(jeu_id) WHERE Jeux.titre = :titre");
        $requete->bindValue(":titre", $titre, PDO::PARAM_STR);
        $requete->execute();
        $result = $requete->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['boite_id'] : null;

        
    }

    public function ajouterPret($boite,$emprun){

        $requete = $this->bd->prepare("INSERT INTO Prets (boite_id, emprunteur_id, date_emprunt, date_retour) 
        VALUES (:boite_id, :emprunteur_id, NOW(), NOW() + INTERVAL 2 WEEK)");
        $requete->bindValue(':boite_id',$boite ,PDO::PARAM_INT);
        $requete->bindValue(':emprunteur_id',$emprun, PDO::PARAM_INT);
        return $requete->execute();


    }

    public function pretExisteDeja($boite_id) {
        $requete = $this->bd->prepare("
            SELECT COUNT(*) AS nb 
            FROM Prets
            WHERE boite_id = :boite_id AND date_retour > NOW()
        ");
        $requete->bindValue(':boite_id', $boite_id, PDO::PARAM_INT);
    
        $requete->execute();
        $result = $requete->fetch(PDO::FETCH_ASSOC);
        return $result['nb'] > 0;
    }



    public function getPretsByEmpruId($emprunteur){


        $requete =$this->bd->prepare("SELECT * FROM Prets where emprunteur_id = :emprunteur_id");
        $requete->bindValue(':emprunteur_id',$emprunteur, PDO::PARAM_INT);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);    
    }


    public function getTitreByEmpruId($em){
        $requete=$this->bd->prepare("SELECT titre FROM Jeux JOIN Boites USING(jeu_id) WHERE boite_id = :boite_id");
        $requete->bindValue(':boite_id',$em,PDO::PARAM_INT);
        $requete->execute();
        $result = $requete->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['titre'] : null;

}
    public function suppPret($pret){
        $requete= $this->bd->prepare("DELETE FROM Prets WHERE pret_id = :pret_id");
        $requete->bindValue(':pret_id',$pret,PDO::PARAM_INT);
        $requete->execute();
    
} 
    public function suppJeu($jeu_id){
         $this->bd->prepare("DELETE FROM Prets WHERE boite_id IN (SELECT boite_id FROM Boites WHERE jeu_id = :jeu_id)")
                 ->execute([':jeu_id' => $jeu_id]);

            $this->bd->prepare("DELETE FROM Boites WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $this->bd->prepare("DELETE FROM JeuAuteur WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $this->bd->prepare("DELETE FROM JeuEditeur WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $this->bd->prepare("DELETE FROM JeuCategories WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $this->bd->prepare("DELETE FROM JeuMecanisme WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $this->bd->prepare("DELETE FROM Jeux WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);
}
    public function modifJeu($jeu_id, $titre, $date_debut, $date_fin, $info_date, $version, $nb_joueurs, $age, $mots_cles, $auteur, $editeur){
        $updateQuery = "UPDATE Jeux 
        SET titre = :titre, 
            date_parution_debut = :date_debut, 
            date_parution_fin = :date_fin, 
            information_date = :info_date, 
            version = :version, 
            nombre_de_joueurs = :nb_joueurs, 
            age_indique = :age, 
            mots_cles = :mots_cles 
        WHERE jeu_id = :jeu_id";

        $updateQuery = $this->bd->prepare($updateQuery);
        
        $updateQuery->bindValue(':titre', $titre, PDO::PARAM_STR);
        $updateQuery->bindValue(':date_debut', $date_debut, PDO::PARAM_STR);
        $updateQuery->bindValue(':date_fin', $date_fin, PDO::PARAM_STR);
        $updateQuery->bindValue(':info_date', $info_date, PDO::PARAM_STR);
        $updateQuery->bindValue(':version', $version, PDO::PARAM_STR);
        $updateQuery->bindValue(':nb_joueurs', $nb_joueurs, PDO::PARAM_STR);
        $updateQuery->bindValue(':age', $age, PDO::PARAM_STR);
        $updateQuery->bindValue(':mots_cles', $mots_cles, PDO::PARAM_STR);
        $updateQuery->bindValue(':mots_cles', $mots_cles, PDO::PARAM_STR);
        $updateQuery->bindValue(':jeu_id', $jeu_id, PDO::PARAM_STR);
        $updateQuery->execute();

        $deleteOldEditeurs = "DELETE FROM JeuEditeur WHERE jeu_id = :jeu_id";
        $deleteOldEditeurs = $this->bd->prepare($deleteOldEditeurs);
        $deleteOldEditeurs->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $deleteOldEditeurs->execute();

        $insertEditeursQuery= "INSERT INTO Editeurs(nom) VALUES(:nom)";
        $insertEditeursQuery = $this->bd->prepare($insertEditeursQuery);
        $insertEditeursQuery->bindValue(':nom', $editeur, PDO::PARAM_STR);
        $insertEditeursQuery->execute();

        $editeur_id = $this->bd->lastInsertId();

        $insertJeuEditeur = "INSERT IGNORE INTO JeuEditeur (jeu_id, editeur_id) VALUES (:jeu_id, :editeur_id)";
        $insertJeuEditeur = $this->bd->prepare($insertJeuEditeur);
        $insertJeuEditeur->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $insertJeuEditeur->bindValue(':editeur_id', $editeur_id, PDO::PARAM_INT);
        $insertJeuEditeur->execute();

        $deleteOldAuteurs = "DELETE FROM JeuAuteur WHERE jeu_id = :jeu_id";
        $deleteOldAuteurs = $this->bd->prepare($deleteOldAuteurs);
        $deleteOldAuteurs->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $deleteOldAuteurs->execute();

        $insertAuteursQuery = "INSERT IGNORE INTO Auteurs(nom) VALUES(:nom)";
        $insertAuteursQuery = $this->bd->prepare($insertAuteursQuery);
        $insertAuteursQuery->bindValue(':nom', $auteur, PDO::PARAM_STR);
        $insertAuteursQuery->execute();

        $auteur_id = $this->bd->lastInsertId();

        $insertJeuAuteur = "INSERT INTO JeuAuteur (jeu_id, auteur_id) VALUES (:jeu_id, :auteur_id)";
        $insertJeuAuteur = $this->bd->prepare($insertJeuAuteur);
        $insertJeuAuteur->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $insertJeuAuteur->bindValue(':auteur_id', $auteur_id, PDO::PARAM_INT);
        $insertJeuAuteur->execute();

        $deleteOldMecanismes = "DELETE FROM JeuMecanisme WHERE jeu_id = :jeu_id";
        $deleteOldMecanismes = $this->bd->prepare($deleteOldMecanismes);
        $deleteOldMecanismes->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $deleteOldMecanismes->execute();

        $insertMecanismeQuery = "INSERT IGNORE INTO Mecanismes(nom) VALUES (:nom)";
        $insertMecanismeStmt = $this->bd->prepare($insertMecanismeQuery);
        $insertMecanismeStmt->bindValue(':nom', $mots_cles, PDO::PARAM_STR);
        $insertMecanismeStmt->execute();

        $mecanisme_id = $this->bd->lastInsertId();

        $insertJeuMecanismeQuery = "INSERT INTO JeuMecanisme (jeu_id, mecanisme_id) VALUES (:jeu_id, :mecanisme_id)";
        $insertJeuMecanismeStmt = $this->bd->prepare($insertJeuMecanismeQuery);
        $insertJeuMecanismeStmt->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $insertJeuMecanismeStmt->bindValue(':mecanisme_id', $mecanisme_id, PDO::PARAM_INT);
        $insertJeuMecanismeStmt->execute();
}

    public function ajouterJeu($form_titre, $form_date_parution, $form_date_parution_fin, $form_auteur, $form_editeur, $form_information_date, $form_version, $form_nb_joueur, $form_age, $form_mots_cles, $form_mecanismes, $form_collection){
            $insertMecanismesQuery= "INSERT INTO Mecanismes(nom) VALUES(:nom)";
    $insertMecanismesQuery = $this->bd->prepare($insertMecanismesQuery);
    $insertMecanismesQuery->bindValue(':nom', $form_mecanismes, PDO::PARAM_STR);
    $insertMecanismesQuery->execute();

    $mecanisme_id = $this->bd->lastInsertId();

    $insertAuteursQuery= "INSERT INTO Auteurs(nom) VALUES(:nom)";
    $insertAuteursQuery = $this->bd->prepare($insertAuteursQuery);
    $insertAuteursQuery->bindValue(':nom', $form_auteur, PDO::PARAM_STR);
    $insertAuteursQuery->execute();

    $auteur_id = $this->bd->lastInsertId();

    $insertEditeursQuery= "INSERT INTO Editeurs(nom) VALUES(:nom)";
    $insertEditeursQuery = $this->bd->prepare($insertEditeursQuery);
    $insertEditeursQuery->bindValue(':nom', $form_editeur, PDO::PARAM_STR);
    $insertEditeursQuery->execute();

    $editeur_id = $this->bd->lastInsertId();

    $insertCollectionQuery= "INSERT INTO Collection(nom) VALUES(:nom)";
    $insertCollectionQuery = $this->bd->prepare($insertCollectionQuery);
    $insertCollectionQuery->bindValue(':nom', $form_collection, PDO::PARAM_STR);
    $insertCollectionQuery->execute();

    $insertJeuQuery= "INSERT INTO Jeux(titre, date_parution_debut, date_parution_fin, information_date, version, nombre_de_joueurs, age_indique, mots_cles, mecanisme_id)
                    VALUES(:titre, :date_parution, :date_parution_fin, :information_date, :version, :nb_joueur, :age, :mots_cles, :mecanisme_id)";
    $insertJeuQuery = $this->bd->prepare($insertJeuQuery);
    $insertJeuQuery->bindValue(':titre', $form_titre, PDO::PARAM_STR);
    $insertJeuQuery->bindValue(':date_parution', $form_date_parution, PDO::PARAM_INT);
    $insertJeuQuery->bindValue(':date_parution_fin', $form_date_parution_fin, PDO::PARAM_INT);
    $insertJeuQuery  ->bindValue(':information_date', $form_information_date, PDO::PARAM_STR);
    $insertJeuQuery  ->bindValue(':version', $form_version, PDO::PARAM_STR);
    $insertJeuQuery  ->bindValue(':nb_joueur', $form_nb_joueur, PDO::PARAM_STR);
    $insertJeuQuery  ->bindValue(':age', $form_age, PDO::PARAM_STR);
    $insertJeuQuery  ->bindValue(':mots_cles', $form_mots_cles, PDO::PARAM_STR);
    $insertJeuQuery->bindValue(':mecanisme_id', $mecanisme_id, PDO::PARAM_INT);
    $insertJeuQuery->execute();

    $jeu_id = $this->bd->lastInsertId();

    $insertJeuMecanismeQuery = "INSERT INTO JeuMecanisme(jeu_id, mecanisme_id) VALUES(:jeu_id, :mecanisme_id)";
    $insertJeuMecanismeQuery = $this->bd->prepare($insertJeuMecanismeQuery);
    $insertJeuMecanismeQuery->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
    $insertJeuMecanismeQuery->bindValue(':mecanisme_id', $mecanisme_id, PDO::PARAM_INT);
    $insertJeuMecanismeQuery->execute();

    $insertJeuAuteurQuery = "INSERT INTO JeuAuteur(jeu_id, auteur_id) VALUES(:jeu_id, :auteur_id)";
    $insertJeuAuteurQuery = $this->bd->prepare($insertJeuAuteurQuery);
    $insertJeuAuteurQuery->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
    $insertJeuAuteurQuery->bindValue(':auteur_id', $auteur_id, PDO::PARAM_INT);
    $insertJeuAuteurQuery->execute();

    $insertJeuEditeurQuery = "INSERT INTO JeuEditeur(jeu_id, editeur_id) VALUES(:jeu_id, :editeur_id)";
    $insertJeuEditeurQuery = $this->bd->prepare($insertJeuEditeurQuery);
    $insertJeuEditeurQuery->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
    $insertJeuEditeurQuery->bindValue(':editeur_id', $editeur_id, PDO::PARAM_INT);
    $insertJeuEditeurQuery->execute();

    return true;
}
}

?>
