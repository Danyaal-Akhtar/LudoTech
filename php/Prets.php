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


    ?>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/logo.png">
    <meta name="description" content="Découvrez-en plus sur notre mission, nos valeurs et notre histoire.">
    <title>LudoTech - Categories</title>
    <link rel="stylesheet" href="/css/Pret.css"> 
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
        </button></div>

        <?php

    $model = Model::getModel();
    if (!isset($_GET['emprunteur'])) {
        die("Emprunteur non spécifié.");
    }


    if (isset($_POST['supprimer'])) {
    $model->suppPret((int)$_POST['id']);
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
    $emprunteur = (int)$_GET['emprunteur'];
    $resultats = $model->getPretsByEmpruId($emprunteur);
    

    ?>
<div class="gestion-prets">
    <h2>Gestion des Prêts</h2>
    <p>Prêts de l'emprunteur ID <?= $emprunteur ?></p>
    <table>
        <thead>
            <tr>
                <th>ID Prêt</th>
                <th>Nom du jeu</th>
                <th>ID Boîte</th>
                <th>Date d'emprunt</th>
                <th>Date de retour</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($resultats as $res): ?>
            <tr>
                <td><?= $res['pret_id'] ?></td>
                 <td><?= $model->getTitreByEmpruId($res['boite_id']) ?></td>
                  <td><?= $res['boite_id'] ?></td>
                <td><?= $res['date_emprunt'] ?></td>
                <td><?= $res['date_retour'] ?></td>
                <td>
                    <?php
                      $now = date('Y-m-d');
                      if ($res['date_retour'] < $now) {
                          echo "<span class='badge retard'>EN RETARD</span>";
                      } elseif ($res['statut'] === 'retourne') {
                          echo "<span class='badge retourne'>RETOURNÉ</span>";
                      } else {
                          echo "<span class='badge actif'>ACTIF</span>";
                      }
                    ?>
                </td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='id' value='<?= $res['pret_id'] ?>'>
                        <button type='submit' name='supprimer' class='btn-supprimer'>Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary">
        <div>Total des prêts : <?= count($resultats) ?></div>
        <div>Prêts actifs : <?= count(array_filter($resultats, fn($r) => $r['statut'] === 'actif')) ?></div>
        <div>Prêts en retard : <?= count(array_filter($resultats, fn($r) => $r['date_retour'] < date('Y-m-d') && $r['statut'] !== 'retourne')) ?></div>
    </div>
</div>
    


    <footer>
        <p>&copy; 2025 LudoTech | Tous droits réservés.</p>
    </footer>
    <script src="/dashboard.js"></script>
/
</body>
</html>
