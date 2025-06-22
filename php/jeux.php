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
    <link rel='stylesheet' href='/css/jeuxxx.css'>
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
   
   
    
    if (preg_match('/^[^\/]+/', $meca, $matches)) { 
        $meca = trim($matches[0]); 
    } else {
        $meca = ''; 
    }

    $index_img = 5; 

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
                 <img alt='cat' class='img_cat' src='/img/cat/" . htmlspecialchars($tab_img[$index_img]) . "'></img>
            </section>";
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

    echo "</main>
    <footer>
        <p>© 2025 LudoTech | Tous droits réservés.</p>
      
    </footer>
    <script src='/dashboard.js'></script>
</body>
</html>";
}

?>
