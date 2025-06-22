<?php
require "Model.php";
require "util.php";
init_php_session();

if(!isset($_SESSION['nom'])){
header("Location: /index.php");
exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="icon" href="/img/logo.png">
    <link rel='stylesheet' href='/css/EnsembleJeux.css'>
    <title>LudoTech - EnsembleJeux </title>
</head>
<body>
    <header>
        <img src="/img/logo.png" alt="LudoTech" class='logo'>
        <div class="search-container">
            <form method="POST" action='/php/search.php'>
                <input type="text" name = "s" placeholder="Rechercher un jeu, un éditeur, ...   ">
            </form>
        </div>
        <div class="profile">
        <?php if(is_logged()): ?>
                <?= htmlspecialchars($_SESSION['nom']) ?>
        <?php else: ?>
            <?=header('location: /index.php'); ?>
        <?php endif; ?>
        <img id='userimg' alt='profile' src="/img/user.png">
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
        <a href="compte.php">Compte</a>
        </button>
        <?php if(is_admin())
            echo "<button class='value'>
                <a href='/php/board.php'>Tableau de Bord</a>
            </button>";
        ?>
        <?php if(is_curator() || is_admin())
            echo "<button class='value'>
                <a href='/php/ajouter.php'>Ajouter un Jeu</a>
            </button>";
        ?>
        <button class="value">
            <a href="logout.php">Déconnexion</a>
        </button>
    </div>
    
    <?php
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  


$model = Model::getModel();
$resultats = $model->getJeux($page);

$count=0;
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


if ($resultats) {
    echo "<div class=liste-jeu>";
    foreach ($resultats as $jeu) {
        echo "<a alt='jeux' href='/php/jeux.php?titre=" . urlencode($jeu['titre']) . "' class='jeu-container' loading='lazy'>
        <img src='/img/cat/" . $tab_img[rand(0, 52)] . "'>
            <p>" . htmlspecialchars($jeu['titre']) . "</p>
        </a>";
        $count++;
        if($count%10==0){
            echo "</div><div class= liste-jeu>";
        }
    }
echo "</div>";   
} else {
    echo "<p>Aucun jeu trouvé.</p>"; 
}

        ?>
        <div class='danyaal'>
            <div class='pagination'>
                <?php
                if($_GET['page']==1){
                    echo "<a href='/php/EnsembleJeux.php?page=".($_GET['page'] + 1 )."'>&raquo;</a>";
                }
                else{        
                
                echo "<a href='/php/EnsembleJeux.php?page=".($_GET['page'] - 1)."'>&laquo;</a>";

                echo "<a href='/php/EnsembleJeux.php?page=".($_GET['page'] + 1 )."'>&raquo;</a>";
                }
                
            
               ?>

            </div> 
        </div>
        
                <footer>
                    <p>© 2025 LudoTech | Tous droits réservés.</p>
                    <p>Mentions légales | Politique de confidentialité</p>
                </footer>

            <script src="/dashboard.js"></script>
        </body>
        </html>
