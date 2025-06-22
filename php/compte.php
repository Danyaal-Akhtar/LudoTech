<?php
require 'util.php';
require 'Model.php';

init_php_session();

if (!is_logged()) {
    header('Location: /index.php'); 
    exit;
}

if (!isset($_SESSION['nom'])) {
    header("Location: /index.php");
    exit();
}

$model = Model::getModel();
$conn = $model->getPDO();

if (isset($_SESSION['nom'])) {
    $query = "SELECT nom, email, role FROM Emprunteurs JOIN Role USING(role_id) WHERE nom = :nom";
    $requete = $conn->prepare($query);
    $requete->execute(['nom' => $_SESSION['nom']]);
    $user = $requete->fetch(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="icon" href="../img/logo.png">
    <link rel='stylesheet' href='../css/compte.css'>
    <title>Mon Compte - LudoTech</title>
</head>
<body>
    <header>
        <img src="../img/logo.png" class='logo' alt="LudoTech">
        <div class="search-container">
            <form method="POST" action='/php/search.php'>
                <input type="text" name = "s" placeholder="Rechercher un jeu, un éditeur, ...   ">
            </form>
        </div>
        <div class="profile">
            <?= htmlspecialchars($user['nom']) ?>
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
    <div class="container">
        <h1>Mon Compte</h1>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
        <a href="../home.php" class="btn">Retour</a>
    </div>
    <footer>
        <p>© 2025 LudoTech | Tous droits réservés.</p>
    </footer>
    <script src='/dashboard.js'></script>
</body>
</html>
