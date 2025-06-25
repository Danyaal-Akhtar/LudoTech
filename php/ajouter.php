<?php
  require 'util.php';
  require 'Model.php';
  init_php_session();

  if(!isset($_SESSION['nom'])) {
    header("Location: /index.php");
    exit();
    }

  if(!is_admin() && !is_curator()) {
    header("Location: /home.php");
    exit();
    }
    
  $model = Model::getModel();
  $conn = $model->getPDO();
  
  if(isset($_POST['Ajouter'])){
    $form_titre = htmlspecialchars($_POST['titre']);
    $form_date_parution = htmlspecialchars($_POST['date_parution']);
    $form_date_parution_fin = htmlspecialchars($_POST['date_parution_fin']);
    $form_auteur = htmlspecialchars($_POST['auteur']);
    $form_editeur = htmlspecialchars($_POST['editeur']);
    $form_information_date = htmlspecialchars($_POST['information_date']);
    $form_version = htmlspecialchars($_POST['version']);
    $form_nb_joueur = htmlspecialchars($_POST['nb_joueur']);
    $form_age = htmlspecialchars($_POST['age']);
    $form_mots_cles = htmlspecialchars($_POST['mots_cles']);
    $form_mecanismes = htmlspecialchars($_POST['mecanismes']);
    $form_collection = htmlspecialchars($_POST['collection']);

    $res = $model->ajouterJeu($form_titre, $form_date_parution, $form_date_parution_fin, $form_auteur, $form_editeur, $form_information_date, $form_version, $form_nb_joueur, $form_age, $form_mots_cles, $form_mecanismes, $form_collection);

    if($res){
        echo '<span id="ajout"> Le Jeu a été Ajouté avec succès !</span>';
    } 
    else {
        echo '<span class="error">.'.'Erreur d\'Insertion, verifiez les formats des champs !'.'</span>';
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="/css/index.css"> 
    <link rel='stylesheet' href='/css/ajouter.css'>
    <title>LudoTech - Ajouter un Jeu</title>
</head>
<body>
    <header>
        <img src="../img/logo.png" alt="LudoTech" class='logo'>
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
        <img id='userimg' alt='profile' src="../img/user.png">
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
    <div class='ajouter'>
        <form class="form" method='POST'>
        <h1 class="title">- Ajouter un Jeu de Société -</h1>
        <div class="form_control">
            <input type="text" name='titre' class="input" required="">
            <label class="label">Titre</label>
        </div>
        <div class="form_control">
            <input type="text" name='date_parution' class="input" required="">
            <label class="label">Date de Parution</label>
        </div>
        <div class="form_control">
            <input type="text" name='date_parution_fin' class="input" required="">
            <label class="label">Date de Parution Fin</label>
        </div>
        <div class="form_control">
            <input type="text" name='auteur' class="input" required="">
            <label class="label">Auteur</label>
        </div>
        <div class="form_control">
            <input type="text" name='editeur' class="input" required="">
            <label class="label">Editeur</label>
        </div>
        <div class="form_control">
            <input type="text" name='information_date' class="input" required="">
            <label class="label">Information date</label>
        </div>
        <div class="form_control">
            <input type="text" name='version' class="input" required="">
            <label class="label">Version</label>
        </div>
        <div class="form_control">
            <input type="text" name='nb_joueur' class="input" required="">
            <label class="label">Nombre de joueurs</label>
        </div>
        <div class="form_control">
            <input type="text" name='age' class="input" required="">
            <label class="label">Age</label>
        </div>
        <div class="form_control">
            <input type="text" name='mots_cles' class="input" required="">
            <label class="label">Mots cles</label>
        </div>
        <div class="form_control">
            <input type="text" name='mecanismes' class="input" required="">
            <label class="label">Mecanismes</label>
        </div>
        <div class="form_control">
            <input type="text" name='collection' class="input" required="">
            <label class="label">Collection</label>
        </div>
        <input type='submit' name='Ajouter' value='Ajouter' id='button'>
        </form>
    </div>
    <footer>
       <p>&copy; 2025 LudoTech | Tous droits réservés.</p>
    </footer>

    <script src="/dashboard.js"></script>
</body>
</html>
