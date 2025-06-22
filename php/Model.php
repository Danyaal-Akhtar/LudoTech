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
    
            if (strlen($search) == 4 && is_numeric($search)) {
               
                $query = $this->bd->prepare('SELECT * FROM Jeux WHERE date_parution_debut = :search ORDER BY jeu_id DESC');
                $query->execute(['search' => $search]);
               
                $queryTitle = $this->bd->prepare('SELECT * FROM Jeux WHERE titre LIKE :search ORDER BY jeu_id DESC');
                $queryTitle->execute(['search' => '%' . $search . '%']);
    
              
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
               
                $resultsTitle = $queryTitle->fetchAll(PDO::FETCH_ASSOC);
    
                $results = array_merge($results, array_diff_key($resultsTitle, $results));
    
                return $results;
            } 
            else {
             
                $query = $this->bd->prepare('SELECT * FROM Jeux WHERE titre LIKE :search ORDER BY jeu_id DESC');
                $query->execute(['search' => '%' . $search . '%']);
                return $query->fetchAll(PDO::FETCH_ASSOC);
            }
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

        $requete = $this->bd->prepare(" INSERT INTO Prets (boite_id, emprunteur_id, date_emprunt, date_retour) 
        VALUES (:boite_id, :emprunteur_id, NOW(), NOW() + INTERVAL 2 WEEK");
        $requete->bindValue(':boite_id',$boite ,PDO::PARAM_INT);
        $requete->bindValue(':emprunteur_id',$emprun, PDO::PARAM_INT);
        return $requete->execute();


    }

    public function pretExisteDeja($boite_id, $emprunteur_id) {
        $requete = $this->bd->prepare("
            SELECT COUNT(*) AS nb 
            FROM Prets
            WHERE boite_id = :boite_id AND emprunteur_id = :emprunteur_id 
        ");
        $requete->bindValue(':boite_id', $boite_id, PDO::PARAM_INT);
        $requete->bindValue(':emprunteur_id', $emprunteur_id, PDO::PARAM_INT);
        $requete->execute();
        $result = $requete->fetch(PDO::FETCH_ASSOC);
        return $result['nb'] > 0;
    }
       
}

?>