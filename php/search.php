<?php
  require 'util.php';
  require 'Model.php';
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
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="icon" href="/img/logo.png">
    <link rel='stylesheet' href='/css/search.css'>
    <title>LudoTech - Recherche</title>
</head>
<body>
<?php 
    $u = $model->rechercheb();

    if ($u) { 
    if (count($u) > 0) { 
        echo "<h2>Résultats de la recherche :</h2><section>
  <div class='list'>
    <ul>";
        foreach ($u as $resultat) {
        echo '<li><p><a href="/php/jeux.php?titre=' . urlencode($resultat['titre']) . '">' . $resultat['titre'] . '</a></p><li>';
        }
        echo "</ul></div><div class='animation'></div></section><br><a href='/home.php' class='btn'>Home</a>";
    } else {
        echo "<p class='p'>Aucun jeu trouvé.</p>";
    }
    }
    else {
        echo "<h1 class='p'>Aucun jeu trouvé.</h1>
        <a href='/home.php' class='btn'>Retour</a>";
    }
    ?>
</body>
</html>