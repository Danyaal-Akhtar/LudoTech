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
    <title>Contactez-nous - LudoTech</title>
      <link rel="stylesheet" href="/css/index.css"> 
    <link rel="stylesheet" href="/css/contact.css">
</head>
<body>
  
<header>
        <img src="../img/logo.png" alt="LudoTech" class='logo'>
        <div class="search-container">
        <form method="POST" action='/php/search.php'>
                <input type="text" name = "s" placeholder="Rechercher un jeu, un éditeur, ...">
        </form>
        </div>
        <div class="profile">
        <?php if(is_logged()): ?>
                <?= htmlspecialchars($_SESSION['nom']) ?>
        <?php else: ?>
            <?=header("Location: /index.php"); ?>
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

    <section class="hero">
        <h1>Contactez-nous</h1>
        <p>Nous sommes disponibles pour répondre à toutes vos questions. Retrouvez toutes nos informations de contact ci-dessous.</p>
    </section>

  
    <section class="contact-info">
        <div class="info-container">
            <h2>📍 Adresse</h2>
            <p> 99 Av. Jean Baptiste Clément,<br>93430 Villetaneuse, France</p>
        </div>
        <div class="info-container">
            <h2>📧 Email</h2>
            <p><a href="mailto:ludotech.pro@gmail.com">ludotech.pro@gmail.com</a></p>
        </div>
        <div class="info-container">
            <h2>📞 Téléphone</h2>
            <p><a href="tel:01 49 40 30 00">01 49 40 30 00</a></p>
        </div>
       
    </section>

    
    <footer>
        <p>&copy; 2025 LudoTech | Tous droits réservés.</p>
      
    </footer>
    <script src="/dashboard.js"></script>
</body>
</html>