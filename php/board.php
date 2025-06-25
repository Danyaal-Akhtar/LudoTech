<?php
  require 'util.php';
  require 'Model.php';
  init_php_session();

    if (!is_logged()) {
    header('Location: /index.php');
    exit;
}
  if(!isset($_SESSION['nom'])) {
    header("Location: /index.php");
    exit();
    }
    if(!is_admin()) {
        header("Location: /home.php");
        exit();
    }

    $model = Model::getModel();
    $conn = $model->getPDO();

    if (isset($_POST['supprimer'])) {
        $id = intval($_POST['id']);
        $supQuery = $conn->prepare("DELETE FROM Emprunteurs WHERE emprunteur_id = :id");
        $supQuery->bindValue(':id', $id, PDO::PARAM_INT);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['role_new_id'])){
        $role_new_id = intval($_POST['role_new_id']);
        if(isset($_POST['id'])){
            $user_id = intval($_POST['id']);
            
            $updateQuery = $conn->prepare("UPDATE Emprunteurs SET role_id = :role_id WHERE emprunteur_id = :user_id");
            $updateQuery->bindValue(':role_id', $role_new_id, PDO::PARAM_INT);
            $updateQuery->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $updateQuery->execute();
        }
    }
    else {
        echo "<p style='color: red;'>Erreur de Modification : " . $conn->error . "</p>";
    }
}
    $userQuery = "SELECT * FROM Emprunteurs WHERE role_id!=1";
    $result = $conn->query($userQuery);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="icon" href="/img/logo.png">
    <link rel='stylesheet' href='../css/board.css'>
    <title>LudoTech - Tableau de Bord</title>
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
        <div class='tabadmin'>
        <h1>Liste des Utilisateurs</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Emprunteur_id</th>
                <th>Role_id</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date création Compte</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $row['emprunteur_id']; ?></td>
                <td><?php echo $row['role_id']; ?>
                <br><br>
                <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['emprunteur_id']; ?>">
                <select name="role_new_id" id="role_new_id">
                            <option value="">-- Choisir un role --</option>
                            <option value="1">Administrateur</option>
                            <option value="2">Currateur</option>
                            <option value="3">Utilisateur</option>
                </select>
                        <br><br>
                    <button type="submit">Modifier</button>
            </form>
            </td>
                <td><?php echo $row['nom']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $row['emprunteur_id']; ?>">
                        <button type="submit" name="supprimer">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php } 
            if (isset($_POST['supprimer'])) {
            if($supQuery->execute()) {
                echo "<p style='color: green;'>Utilisateur supprimé avec succès.</p>";
            } else {
                echo "<p style='color: red;'>Erreur lors de la suppression : " . $conn->error . "</p>";
            }}
            if (isset($_POST['role_new_id'])) {
                if($updateQuery->execute()) {
                    echo "<p style='color: green;'>Role d'Utilisateur modifié avec succès.</p>";
                } else {
                    echo "<p style='color: red;'>Erreur lors de la modification : " . $conn->error . "</p>";
            }}
            ?>
        </tbody>
    </table>
        </div>
    <footer>
        <p>© 2025 LudoTech | Tous droits réservés.</p>
        <p>Mentions légales | Politique de confidentialité</p>
    </footer>

    <script src="/dashboard.js"></script>
</body>
</html>