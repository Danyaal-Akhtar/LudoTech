<?php
  require 'php/Model.php';
  require 'php/util.php';
  init_php_session();
  $model = Model::getModel();
  $conn = $model->getPDO();
  
  if(isset($_POST['SignIn'])){
    $form_username = htmlspecialchars($_POST['form_username']);
    $form_email = $_POST['form_email'];
    $form_password = password_hash(trim($_POST['form_password']), PASSWORD_BCRYPT);

    $checkQuery = "SELECT * FROM Emprunteurs WHERE email = :email OR nom = :nom";
    $result = $conn->prepare($checkQuery);
    $result->bindValue(':email', $form_email, PDO::PARAM_STR);
    $result->bindValue(':nom', $form_username, PDO::PARAM_STR);
    $result->execute();
    if($result->rowCount()>0){
      echo '<span id="error">'.'Email ou Nom d\'utilisateur existant !'.'</span>';
    }
    else{
      $insertQuery= "INSERT INTO Emprunteurs(nom, email, password)
                      VALUES(:nom, :email, :password)";
          $insertQuery = $conn->prepare($insertQuery);
          $insertQuery->bindValue(':nom', $form_username, PDO::PARAM_STR);
          $insertQuery->bindValue(':email', $form_email, PDO::PARAM_STR);
          $insertQuery  ->bindValue(':password', $form_password, PDO::PARAM_STR);
        if($insertQuery->execute()){
          header('location: index.php');
          '<span id="signIn">'.'Votre compte a été créé avec succès !'.'</span>';
          exit;
        } 
        else {
          echo '<span id="error">.'.'Erreur de Connexion'.'</span>';
      }
}
}

  if(isset($_POST['LogIn'])){
    $form_username = htmlspecialchars($_POST['form_username']);
    $form_password = $_POST['form_password'];

    $verifyQuery = 'SELECT * FROM Emprunteurs WHERE nom=:nom';

    $check = $conn->prepare($verifyQuery);
    $check->execute(['nom' => $form_username]);

    $result = $check->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $nom = $result['nom'];
        $rank = $result['role_id'];
        $bcrypt_pass = $result['password'];

        if (password_verify($form_password, $bcrypt_pass)) {
            $_SESSION['nom'] = $nom;          
            $_SESSION['role_id'] = $rank;  
            $_SESSION['emprunteur_id'] = $result['emprunteur_id'];    
            header('location: home.php');
        } else {
            echo '<span id="error">Nom d\'utilisateur ou mot de passe incorrect</span>';
        }
    } else {
        echo '<span id="error">Nom d\'utilisateur ou mot de passe incorrect</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="icon" href="img/logo.png">
    <link rel='stylesheet' href='css/loginn.css'>
    <title>LudoTech</title>
</head>

<body>
<?php if(isset($_SESSION['nom'])): ?>
    <?=header("Location: /home.php"); ?>
<?php endif; ?>
<div class="container">
  <input type="checkbox" id="register_toggle">
  <div class="slider">
    <form class="form" method='POST'>
    <img class='logo' src="img/logo.png" alt="LudoTech">
    <div class="form_control">
        <input type="text" name='form_username' class="input" required="">
        <label class="label">Nom</label>
    </div>
      
      <div class="form_control">
        <input type="password" name='form_password' class="input" required="">
        <label class="label">Mot de passe</label>
      </div>
      <input type='submit' name='LogIn' value='Login' id='button'>
      

      <span class="bottom_text">Pas de compte LudoTech ? <label for="register_toggle" class="swtich"> Inscription</label> </span>
    </form>

    <form class="form" method='POST'>
      <span class="title">Inscription</span>
      <div class="form_control">
        <input type="text" name='form_username' class="input" required="">
        <label class="label">Nom</label>
      </div>

      <div class="form_control">
        <input type="email" name='form_email' class="input" required="">
        <label class="label">Email</label>
      </div>

      <div class="form_control">
        <input type="password" name='form_password' class="input" required="">
        <label class="label">Mot de passe</label>
      </div>

      <input type='submit' name='SignIn' value='Inscription' id='button'>

      <span class="bottom_text">Déjà un compte LudoTech ? <label for="register_toggle" class="swtich">Login</label> </span>
    </form>
    
    
    </div>
</div>
</body>
</html>