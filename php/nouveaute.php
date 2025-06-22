<?php
require_once 'Model.php'; 
require_once 'util.php'; 
init_php_session();

// Vérifier si l'utilisateur est connecté
if (!is_logged()) {
    header('Location: /index.php'); // Redirection vers la page de connexion si non connecté
    exit;
}

// Instancier le modèle pour interagir avec la base de données
$model = Model::getModel();

// Obtenir les nouveautés (par exemple, les 10 derniers jeux ajoutés)
try {
    $nouveautes = $model->getPDO()->prepare("SELECT * FROM Jeux ORDER BY jeu_id DESC LIMIT 10");
    $nouveautes->execute();
    $jeux = $nouveautes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des nouveautés : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logo.png">
    <title>Nouveautés</title>
    <link rel="stylesheet" href="/css/nouveaute.css"> 
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
            <?=header("Location: /index.php"); ?>
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
    
    <main>
        <h2>TOUTES LES NOUVEAUTÉS :</h2>
        <?php if (is_admin()): ?>
            <p class='textco'<strong>Vous êtes connecté en tant qu'administrateur.</strong></p>
        <?php else: ?>
            <strong>Vous êtes connecté en tant qu'utilisateur.</strong>
        <?php endif; ?>
        <?php if (!empty($jeux)): ?>
            <ul>
                <?php foreach ($jeux as $jeu): ?>
                    <li>
                        <h3><?= htmlspecialchars($jeu['titre']); ?></h3>
                        <p>Date de parution : <?= htmlspecialchars($jeu['date_parution_debut'] ?? 'Non spécifiée'); ?></p>
                        <p>Nombre de joueurs : <?= htmlspecialchars($jeu['nombre_de_joueurs'] ?? 'Non spécifié'); ?></p>
                        <p>Âge : <?= htmlspecialchars($jeu['age_indique'] ?? 'Non spécifiée'); ?></p>
                        <a href="jeux.php?titre=<?= urlencode($jeu['titre']); ?>">Voir les détails</a>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune nouveauté disponible pour le moment.</p>
        <?php endif; ?>
    </main>
    
    <footer>
        <p>© 2025 LudoTech | Tous droits réservés.</p>
        <p>Mentions légales</a> | Politique de confidentialité</a></p>
    </footer>
    <script src="/dashboard.js"></script>
</body>
</html>
