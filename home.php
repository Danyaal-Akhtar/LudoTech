<?php
  require 'php/util.php';
  require 'php/Model.php';
  init_php_session();
  $model = Model::getModel();

  if(!isset($_SESSION['nom'])) {
        header("Location: /index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/logo.png">
    <link rel='stylesheet' href='css/style.css'>
    <title>LudoTech</title>
</head>
<body>
    <header>
        <img src="img/logo.png" alt="LudoTech" class='logo'>
        <div class="search-container">
            <form method="POST" action='php/search.php'>
                <input type="text" name = "s" placeholder="Rechercher un jeu, un éditeur, ...   ">
            </form>
        </div>
        <div class="profile">
        <?php if(is_logged()): ?>
                <?= htmlspecialchars($_SESSION['nom']) ?>
        <?php else: ?>
            <?=header("Location: /index.php"); ?>
        <?php endif; ?>
        <img id='userimg' alt='profile' src="img/user.png">
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
            <a href='/php/compte.php'>Compte</a>
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
            <a href="php/logout.php">Déconnexion</a>
        </button>
    </div>
    <div class="games">
        <button class="arrow-buttons" onclick="plusDivs(-1)">&#10094;</button>
         <?php
            $tab = $model->getHomeGames();
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
            foreach($tab as $val){
                echo "<div class='card' style='background-image:url(img/cat/".$tab_img[rand(0,52)].")' ><a href='/php/jeux.php?titre=".$val['titre']."'>".str_replace('?', 'é', $val['titre'])."</a></div>";
            }
         ?>
        <button class="arrow-buttons" onclick="plusDivs(+1)">&#10095;</button>
    </div>
    <footer>
        <p>© 2025 LudoTech | Tous droits réservés.</p>
       
    </footer>

    <script src="script.js"></script>
</body>
</html>
