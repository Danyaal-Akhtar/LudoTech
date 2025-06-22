<?php
require 'util.php';
require "Model.php";
init_php_session();

if (!is_logged()) {
    header('Location: /index.php'); 
    exit;
}
if(!isset($_SESSION['nom'])){
    header("Location: /index.php");
    exit();
}

if (isset($_GET['titre']) && !empty($_GET['titre'])) {
    $titre = $_GET['titre'];
    $model = Model::getModel();

    $resultats = $model->getJeuByTitre($titre);
    echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Détails du Jeu</title>
    <link rel='icon' href='/img/logo.png'>
    <link rel='stylesheet' href='/css/jeux.css'>
</head>
<body>
    <header>
        <img src='../img/logo.png' alt='LudoTech' class='logo'>
        <div class='profile'>";
        
if(is_logged()):
    echo htmlspecialchars($_SESSION['nom']);
else:
    header('location: /index.php');
endif;

echo '<img id="userimg" alt="profile" src="../img/user.png">
</div>
</header>
<nav>
    <a href="/php/nouveaute.php">Nouveauté</a>
    <a href="/php/EnsembleJeux.php?page=1">Ensemble des Jeux</a>
    <a href="/php/categorie.php">Catégories</a>
    <a href="/php/a-propos.php">À propos</a>
    <a href="/php/contact.php">Contactez-nous</a>
</nav>

<div class="dashboard" style="display: none;">
    <button class="value">
        <a href="/php/compte.php">Compte</a>
    </button>';

if (is_admin()) {
    echo '<button class="value">
        <a href="/php/board.php">Tableau de Bord</a>
    </button>';
}

if (is_curator() || is_admin()) {
    echo '<button class="value">
        <a href="/php/ajouter.php">Ajouter un Jeu</a>
    </button>';
}

echo '<button class="value">
        <a href="/php/logout.php">Déconnexion</a>
    </button>
</div>

<main class="game-details">';
    $tab_cat = ['Adresse', 'Affrontement', 'Alliance', 'Association',
    'Asymétrie', 'Bluff', 'Calcul', 'Chance', 'Collecte',
    'Combinaison / collection', 'Connaissances', 'Connexion',
    'Construction', 'Contre la montre', 'Coopération',
    'Course', 'Création', 'Deck building', 'Déduction',
    'Déplacement / exploration', 'Dessin', 'Draft',
    'Enchère', 'Equilibre', 'Expression écrite',
    'Expression gestuelle', 'Expression orale',
    'Gage', 'Gestion de main', 'Gestion de ressources',
    'Guessing', 'Hasard', 'Jeu de rôles', 'Lancers de dés',
    'Majorité / influence', 'Mémoire', 'Mise / enchère',
    'Narration / Scénario', 'Négoce / marchandage',
    'Objectif secret', 'Observation / écoute', 'Parcours',
    "Placement d'ouvriers", 'Placement de tuiles', 'Plis',
    'Programmation', 'Question réponse', 'Rapidité / réflexes',
    'Roll and write', 'Stop ou encore', 'Toucher / odorat', 'Vote', 'NR'];

    $tab_img = ["Adresse.jpg", "Affrontement.jpg", "alliance.webp" , "Association.jpg",
         "Asymétrie.jpg" , "bluff.jpg", "calcul.webp", "chance.jpg", "collecte.webp", 
         "Combinaisoncollection.jpg", "connaissance.jpg", "connextion.jpg" , "construction.jpg" , 
        "Contrelamontre.jpg", "Coopération.jpg" , "course.jpg", "Création.jpeg", "Deckbuilding.jpg",
         "Déduction.jpg", "Déplacementexploration.jpg" , "dessin.webp", "Draft.jpg", "enchère.jpg", "equilibre.jpg", 
         "expressionecrite.webp", "Expressiongestuelle.webp", "Expressionorale.webp", "gage.jpg", 
         "Gestiondemain.jpg", "Gestionderessources.png", "Guessing.jpg", "Hasard.jpg", "Jeuderôles.jpg", 
         "lanceedes.jpg", "Majoritéinfluence.png", "Memoire.jpg", "Miseenchère.webp", "NarrationScénario.jpg", 
         "Négocemarchandage.jpg", "Objectifsecret.jpg",  "Observationécoute.jpg", "origami.jpg", "Parcours.jpg",
          "Placementdetuiles.jpeg", "Placementdouvriers.webp", "Programmation.avif", "Questionreponse.webp",
           "Rapiditéréflexes.jpg", "Rollandwrite.jpg", "Stopouencore.png", "Toucherodorat.jpeg", "vote.jpg", "apropos.jpg"];

   $mecanisme = $model->getMecanismesByJeu($titre);
   $meca =  $mecanisme[0]['nom'];
   
   
    
    if (!empty($mecanisme) && isset($mecanisme[0]['nom'])) {
        $meca = $mecanisme[0]['nom'];

        if (is_string($meca) && preg_match('/^[^\/]+/', $meca, $matches)) {
            $meca = trim($matches[0]);
        } else {
            $meca = '';
        }
    }

    $index_img = 5; 

    //dwdww

    $regex_meca = '/' . preg_quote($meca, '/') . '/i';

    foreach ($tab_cat as $key => $val) {
        if (preg_match($regex_meca, $val)) { 
            $index_img = $key;
            break;
        }
    }

    if (!empty($resultats)) {
        foreach ($resultats as $res) {
            echo "<section class='game-card'>
                <h1>" . htmlspecialchars($titre) . "</h1>
                <p><strong>Date de parution début :</strong> " . htmlspecialchars($res['date_parution_debut'] ?? 'Non renseignée') . "</p>
                <p><strong>Date de parution fin :</strong> " . 
                (empty($res['date_parution_fin']) || trim($res['date_parution_fin']) === '0000' ? 
                'Non renseignée' : htmlspecialchars($res['date_parution_fin'])) . "</p>
                <p><strong>Informations sur la date :</strong> " . htmlspecialchars($res['information_date'] ?? 'Non renseignée') . "</p>
                <p><strong>Version :</strong> " . htmlspecialchars($res['version'] ?? 'Non renseignée') . "</p>
                <p><strong>Nombre de joueurs :</strong> " . htmlspecialchars($res['nombre_de_joueurs'] ?? 'Non renseignée') . "</p>
                <p><strong>Âge :</strong> " . htmlspecialchars($res['age_indique'] ?? 'Non renseignée') . "</p>
                <p><strong>Description (mots-clés) :</strong> " . nl2br(htmlspecialchars($res['mots_cles'] ?? 'Non renseignée')) . "</p>
                <img alt='cat' class='img_cat' src='/img/cat/" . htmlspecialchars($tab_img[$index_img]) . "'></img>";
            if(is_curator() || is_admin()){
                echo "<input type='image' src='/img/crayon.png' id='edit' onclick='document.querySelector(\".modify-game\").style.display=\"block\"'/>
                <input type='image' src='/img/poubelle.png' class='poubelle' onclick='document.querySelector(\".pop-up-delete\").style.display=\"block\"'/>
            </section>";}
            break;
        }
    } else {
        echo "<p>Aucun jeu trouvé pour le titre : " . htmlspecialchars($titre) . ".</p>";
    }

    $editeur = $model->getEditeurByTitre($titre);
    if (!empty($editeur)) {
        echo "<section class='additional-info'>
            <h3>Informations supplémentaires</h3>
            <p><strong>Éditeur :</strong> " . htmlspecialchars($editeur['nom']) . "</p>";
    } else {
        echo "<p>Aucun éditeur trouvé pour ce jeu.</p>";
    }

    $auteurs = $model->getAuteursByTitre($titre);
    if (!empty($auteurs)) {
        echo "<p><strong>Auteur :</strong> " . htmlspecialchars($auteurs['nom']) . "</p>
        </section>";
    } else {
        echo "<p>Aucun auteur trouvé pour ce jeu.</p>";
    }

    if (isset($_POST['delete'])) {
    $model = Model::getModel();
    $resultat = $model->getJeuByTitre($_GET['titre']);

    if (!empty($resultat)) {
        $jeu_id = $resultat[0]['jeu_id'];
        try {
            $conn = $model->getPDO();

            $conn->prepare("DELETE FROM Prets WHERE boite_id IN (SELECT boite_id FROM Boites WHERE jeu_id = :jeu_id)")
                 ->execute([':jeu_id' => $jeu_id]);

            $conn->prepare("DELETE FROM Boites WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $conn->prepare("DELETE FROM JeuAuteur WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $conn->prepare("DELETE FROM JeuEditeur WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $conn->prepare("DELETE FROM JeuCategories WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $conn->prepare("DELETE FROM JeuMecanisme WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            $conn->prepare("DELETE FROM Jeux WHERE jeu_id = :jeu_id")
                 ->execute([':jeu_id' => $jeu_id]);

            header("Location: /home.php");
            exit;

        } catch (Exception $e) {
            echo "Erreur de suppression.";
            }
        }
    }   
    if (isset($_POST['Modifier'])) {
        $model = Model::getModel();
        $conn = $model->getPDO();
        $resultat = $model->getJeuByTitre($_GET['titre']);
        
        $jeu_id = $resultat[0]['jeu_id'];
        $titre = $_POST['titre_modif'];
        $date_debut = $_POST['date_debut_modif'];
        $date_fin = $_POST['date_fin_modif'];
        $info_date = $_POST['info_date_modif'];
        $version = $_POST['version_modif'];
        $nb_joueurs = $_POST['nb_joueurs_modif'];
        $age = $_POST['age_modif'];
        $mots_cles = $_POST['mots_cles_modif'];
        $auteur = $_POST['auteur'];
        $editeur = $_POST['editeur'];

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

        $updateQuery = $conn->prepare($updateQuery);
        
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
        $deleteOldEditeurs = $conn->prepare($deleteOldEditeurs);
        $deleteOldEditeurs->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $deleteOldEditeurs->execute();

        $insertEditeursQuery= "INSERT INTO Editeurs(nom) VALUES(:nom)";
        $insertEditeursQuery = $conn->prepare($insertEditeursQuery);
        $insertEditeursQuery->bindValue(':nom', $editeur, PDO::PARAM_STR);
        $insertEditeursQuery->execute();

        $editeur_id = $conn->lastInsertId();

        $insertJeuEditeur = "INSERT IGNORE INTO JeuEditeur (jeu_id, editeur_id) VALUES (:jeu_id, :editeur_id)";
        $insertJeuEditeur = $conn->prepare($insertJeuEditeur);
        $insertJeuEditeur->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $insertJeuEditeur->bindValue(':editeur_id', $editeur_id, PDO::PARAM_INT);
        $insertJeuEditeur->execute();

        $deleteOldAuteurs = "DELETE FROM JeuAuteur WHERE jeu_id = :jeu_id";
        $deleteOldAuteurs = $conn->prepare($deleteOldAuteurs);
        $deleteOldAuteurs->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $deleteOldAuteurs->execute();

        $insertAuteursQuery = "INSERT IGNORE INTO Auteurs(nom) VALUES(:nom)";
        $insertAuteursQuery = $conn->prepare($insertAuteursQuery);
        $insertAuteursQuery->bindValue(':nom', $auteur, PDO::PARAM_STR);
        $insertAuteursQuery->execute();

        $auteur_id = $conn->lastInsertId();

        $insertJeuAuteur = "INSERT INTO JeuAuteur (jeu_id, auteur_id) VALUES (:jeu_id, :auteur_id)";
        $insertJeuAuteur = $conn->prepare($insertJeuAuteur);
        $insertJeuAuteur->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $insertJeuAuteur->bindValue(':auteur_id', $auteur_id, PDO::PARAM_INT);
        $insertJeuAuteur->execute();

        $deleteOldMecanismes = "DELETE FROM JeuMecanisme WHERE jeu_id = :jeu_id";
        $deleteOldMecanismes = $conn->prepare($deleteOldMecanismes);
        $deleteOldMecanismes->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $deleteOldMecanismes->execute();

        $insertMecanismeQuery = "INSERT IGNORE INTO Mecanismes(nom) VALUES (:nom)";
        $insertMecanismeStmt = $conn->prepare($insertMecanismeQuery);
        $insertMecanismeStmt->bindValue(':nom', $mots_cles, PDO::PARAM_STR);
        $insertMecanismeStmt->execute();

        $mecanisme_id = $conn->lastInsertId();

        $insertJeuMecanismeQuery = "INSERT INTO JeuMecanisme (jeu_id, mecanisme_id) VALUES (:jeu_id, :mecanisme_id)";
        $insertJeuMecanismeStmt = $conn->prepare($insertJeuMecanismeQuery);
        $insertJeuMecanismeStmt->bindValue(':jeu_id', $jeu_id, PDO::PARAM_INT);
        $insertJeuMecanismeStmt->bindValue(':mecanisme_id', $mecanisme_id, PDO::PARAM_INT);
        $insertJeuMecanismeStmt->execute();

        header("Location: jeux.php?titre=" . urlencode($titre));
        exit;
    }

    if (isset($_POST['pret'])) {
                    
        try {   
    $boite_id = $model->getBoiteIdParTitre($_GET['titre']);}
    catch (Exception $e) {echo "erreur1"; }
    
    $pretDejaFait = false;
    if ($boite_id) { 
        $pretDejaFait = $model->pretExisteDeja($boite_id, $_SESSION['emprunteur_id']);
        if ($pretDejaFait) {
            echo "<p> Vous avez déjà emprunté ce jeu.</p>";
        } else {
        

        try{
        $pret = $model->ajouterPret($boite_id, $_SESSION['emprunteur_id']);}
        catch (Exception $e) {echo "erreur2"; }
        if ($pret) {
            
            echo "<p> Prêt ajouté avec succès</p>"; 
            $pretDejaFait = true;
        } else {
            echo "<p>Erreur lors de l'ajout du prêt</p>";
        }
    }}
        else {
        echo "<p> Boîte non trouvée pour ce titre</p>";
    }
    }

    else{
        $boite_id = $model->getBoiteIdParTitre($_GET['titre']);
        if ($boite_id) {
            $pretDejaFait = $model->pretExisteDeja($boite_id, $_SESSION['emprunteur_id']);
        } else {  $pretDejaFait = false;
    }
    }


    echo "<form method='post'>";
    echo "<input type='hidden' name='pret' value='1'>";
    if ($pretDejaFait) {
    echo "<button type='submit' disabled>Faire un prêt</button>";
    } else {
    echo "<button type='submit'>Faire un prêt</button>";
    }
    echo "</form>";

    $resultat = $model->getJeuByTitre($_GET['titre']);
    $editeur = $model->getEditeurByTitre($titre);
    $auteurs = $model->getAuteursByTitre($titre);
    
    echo "</main>";
    if(is_curator() || is_admin()){ // On affiche les pop-up uniquement pour les curateurs et admin
    echo "<div class='modify-game'>
        <h1>Modifier Informations<h1>
        <form method='POST'>
            <input type='text' value='".htmlspecialchars($resultat[0]['titre'], ENT_QUOTES)."' name='titre_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['date_parution_debut'], ENT_QUOTES)."' name='date_debut_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['date_parution_fin'], ENT_QUOTES)."' name='date_fin_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['information_date'], ENT_QUOTES)."' name='info_date_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['version'], ENT_QUOTES)."' name='version_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['nombre_de_joueurs'], ENT_QUOTES)."' name='nb_joueurs_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['age_indique'], ENT_QUOTES)."' name='age_modif'>
            <input type='text' value='".htmlspecialchars($resultat[0]['mots_cles'], ENT_QUOTES)."' name='mots_cles_modif'>
            <input type='text' value='".htmlspecialchars($auteurs['nom'], ENT_QUOTES)."' name='auteur'>
            <input type='text' value='".htmlspecialchars($editeur['nom'], ENT_QUOTES)."' name='editeur'>
            <div>
                <input type='button' value='Annuler' class='btn_modifier' onclick='document.querySelector(\".modify-game\").style.display=\"none\"' />
                <input type='submit' class='btn_modifier' name='Modifier' value='Modifier' id='button'>            
            </div>    
        </form>
    </div>

    </div>
    <div class='pop-up-delete'>
        <p>
        Êtes vous sûr de vouloir supprimer ce Jeu ? 
        </p>
        <div class='btn-delete'>
            <input type='submit' value='Annuler' onclick='document.querySelector(\".pop-up-delete\").style.display=\"none\"' />
        <form method='POST'>
            <input type='submit' value='Supprimer' name='delete'>
        </form>
        </div>
    </div>";} 
    echo "<footer>
        <p>© 2025 LudoTech | Tous droits réservés.</p>
      
    </footer>
    <script src='/dashboard.js'></script>
</body>
</html>";
}

?>