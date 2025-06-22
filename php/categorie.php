<?php
require "Model.php";
require "util.php";
init_php_session();

    if(!isset($_SESSION['nom'])) {
        header("Location: /index.php");
        exit();
    }
    if (!is_logged()) {
    header('Location: /index.php'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/logo.png">
    <meta name="description" content="Découvrez-en plus sur notre mission, nos valeurs et notre histoire.">
    <title>LudoTech - Categories</title>
    <link rel="stylesheet" href="/css/categories.css"> 
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
            <img id="userimg" alt="profile" src="/img/user.png">
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
            <a href="/php/logout.php">Déconnexion</a>
        </button>
    </div>
        <?php
        $model = Model::getModel();
        $resultats = $model->getCategorie();
        $compter = $model->countJeuxParMecanisme();
      


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


    

       if(isset($_GET['meca'])){
            $model = Model::getModel();
            $jeux= $model->getJeuByMecanismes($_GET['meca']); 
            //ff
           
        

            echo "<div class= taille><div class=liste-jeu>";
            foreach ($jeux as $j) {
                echo "<a href='/php/jeux.php?titre=" . urlencode($j['titre']) . "' class='jeu-container'>
                <img src='/img/cat/" . $tab_img[rand(0, 52)] . "'>
                <p>" . htmlspecialchars($j['titre']) . "</p>
            </a>";
            $count++;
            if($count%10==0){
                echo "</div><div class= liste-jeu>";
            }
        }
        echo "</div></div>";   
        } else {
          echo "<div class='table-contenair'><table><tbody>";
          echo "<tr><th>Mécanisme :</th><th>Nombre de Jeux :</th></tr>"; 
        foreach($resultats as $id=>$res){
            $nombre_jeux = isset($compter[$id]) ? $compter[$id]['nombre_jeux'] : 0; 
            echo '<tr>';
            echo "<td><a href = '/php/categorie.php?meca=".$res['nom']."' class='cate' >".$res['nom']."</a></td>";
        
            echo "<td>".htmlspecialchars($nombre_jeux)."</td>";
            echo "</tr>";
        }
        
        }
        echo "</body></table></div>";
        

        ?>
       
       <footer>
        <p>&copy; 2025 LudoTech | Tous droits réservés.</p>
        
    </footer>
    <script src="/dashboard.js"></script>
</body>
</html>