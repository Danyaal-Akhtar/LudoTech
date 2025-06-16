<?php
    require 'util.php';
    init_php_session();

    if(!isset($_SESSION['nom'])) {
        header("Location: /index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/logo.png">
    <meta name="description" content="Découvrez-en plus sur notre mission, nos valeurs et notre histoire.">
    <title>À propos</title>
    <link rel="stylesheet" href="/css/a-propos.css"> 
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
            <img id="userimg" alt="profile" src="../img/user.png">
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
    <main>
        <section class="hero">
            <h1>À PROPOS </h1>
            <p>L'université Sorbonne Paris Nord héberge une collection de près de 17000 jeux de sociétés en facilitant le nettoyage, l’importation et la structuration des données dont certains remontent au XIXe siècle. Une partie (le legs initial) a été indexée dans un fichier Excel unique. Notre site offre donc une interface intuitive pour consulter ensemble des jeux, administrer ces derniers, auteurs et éditeurs, ainsi que le suivi des prêts et de la localisation.
            

        <section class="team">
            <h2>Rencontrez notre équipe</h2>
            <p>Rencontrez les membres de notre équipe, des passionnés d'informatique qui travaillent ensemble afin d'avoir la meilleure experience utilisateur. <a href='/php/contact.php'>Contactez-nous</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 LudoTech | Tous droits réservés.</p>
        Mentions légales | Politique de confidentialité</p>
    </footer>
    <script src="../dashboard.js"></script>
</body>
</html>

