<?php
require 'util.php';
require 'Model.php';
init_php_session();

if (!is_logged()) {
    header('Location: /index.php');
    exit;
}

$model = Model::getModel();
$db = $model->getPDO();

// Récupération des données utilisateur
$user = $db->prepare("SELECT * FROM Emprunteurs WHERE emprunteur_id = :id");
$user->execute(['id' => $_SESSION['emprunteur_id']]);
$user = $user->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $telephone = preg_replace('/[^0-9+]/', '', $_POST['telephone']);
    $current_password = $_POST['current_password'];
    $new_password = !empty($_POST['new_password']) ? trim($_POST['new_password']) : null;

    // Validation du mot de passe actuel
    if (!password_verify($current_password, $user['password'])) {
        $errors[] = "Le mot de passe actuel est incorrect";
    }

    // Validation des données
    if (empty($nom)) {
        $errors[] = "Le nom complet est requis";
    }
    
    if (!$email) {
        $errors[] = "L'adresse email n'est pas valide";
    }
    
   

    // Validation du nouveau mot de passe (obligatoire 8 caractères minimum)
    if (!empty($new_password) && strlen($new_password) < 8) {
        $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères";
    }

    if (empty($errors)) {
        try {
            $update_data = [
                'nom' => $nom,
                'email' => $email,
                'telephone' => $telephone,
                'id' => $user['emprunteur_id']
            ];

            $update_query = "UPDATE Emprunteurs SET nom = :nom, email = :email, telephone = :telephone";

            if ($new_password) {
                $update_query .= ", password = :password";
                $update_data['password'] = password_hash($new_password, PASSWORD_BCRYPT);
            }

            $update_query .= " WHERE emprunteur_id = :id";

            $stmt = $db->prepare($update_query);
            if ($stmt->execute($update_data)) {
                $_SESSION['nom'] = $nom;
                $_SESSION['email'] = $email;
                $success = true;
                
                // Recharger les données utilisateur
                $user_refresh = $db->prepare("SELECT * FROM Emprunteurs WHERE emprunteur_id = :id");
                $user_refresh->execute(['id' => $_SESSION['emprunteur_id']]);
                $user = $user_refresh->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Compte - LudoTech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/comptee.css">
</head>
<body>
    <div class="container">
        <header class="account-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="header-text">
                    <h1>Gestion du Compte</h1>
                    <p class="subtitle">Gérez vos informations personnelles et paramètres de sécurité</p>
                </div>
            </div>
        </header>

        <main class="account-content">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <h3>Erreurs détectées</h3>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h3>Mise à jour réussie</h3>
                        <p>Vos informations ont été mises à jour avec succès.</p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="account-form">
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="section-title">
                            <h2>Informations Personnelles</h2>
                            <p>Modifiez vos données personnelles et coordonnées</p>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom" class="form-label">
                                <i class="fas fa-user"></i>
                                Nom Complet
                            </label>
                            <input 
                                type="text" 
                                id="nom" 
                                name="nom" 
                                class="form-input" 
                                value="<?= htmlspecialchars($user['nom']) ?>" 
                                required
                                placeholder="Votre nom complet"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Adresse Email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                value="<?= htmlspecialchars($user['email']) ?>" 
                                required
                                placeholder="votre.email@exemple.com"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="telephone" class="form-label">
                                <i class="fas fa-phone"></i>
                                Numéro de Téléphone
                            </label>
                            <input 
                                type="tel" 
                                id="telephone" 
                                name="telephone" 
                                class="form-input" 
                                value="<?= htmlspecialchars($user['telephone'] ?? '') ?>"
                                placeholder="0123456789"
                            >
                            <small class="form-help">Format: 10 chiffres minimum</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="section-title">
                            <h2>Sécurité du Compte</h2>
                            <p>Modifiez votre mot de passe pour sécuriser votre compte</p>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key"></i>
                                Mot de Passe Actuel
                            </label>
                            <div class="password-field">
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="form-input" 
                                    required
                                    placeholder="Votre mot de passe actuel"
                                >
                                <button type="button" class="password-toggle" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Nouveau Mot de Passe
                            </label>
                            <div class="password-field">
                                <input 
                                    type="password" 
                                    id="new_password" 
                                    name="new_password" 
                                    class="form-input" 
                                    minlength="8"
                                    placeholder="Nouveau mot de passe (8 caractères min.)"
                                >
                                <button type="button" class="password-toggle" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength-container">
                                <div class="password-strength-bar">
                                    <div class="password-strength-fill"></div>
                                </div>
                                <small class="password-strength-text">Minimum 8 caractères requis</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer les Modifications
                    </button>
                    <a href="/home.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        retour
                    </a>
                </div>
            </form>
        </main>
    </div>

    <script>
        // Gestion de l'affichage/masquage des mots de passe
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Indicateur de force du mot de passe
        const newPasswordInput = document.getElementById('new_password');
        const strengthFill = document.querySelector('.password-strength-fill');
        const strengthText = document.querySelector('.password-strength-text');

        if (newPasswordInput && strengthFill && strengthText) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const length = password.length;
                
                let strength = 0;
                let strengthLabel = '';
                let strengthColor = '';

                if (length === 0) {
                    strengthLabel = 'Minimum 8 caractères requis';
                    strengthColor = '#ccc';
                } else if (length < 8) {
                    strength = (length / 8) * 25;
                    strengthLabel = `${8 - length} caractères manquants`;
                    strengthColor = '#e74c3c';
                } else {
                    strength = 25;
                    strengthLabel = 'Faible';
                    strengthColor = '#e74c3c';
                    
                    // Vérifications supplémentaires pour la force
                    if (/[A-Z]/.test(password)) strength += 25;
                    if (/[0-9]/.test(password)) strength += 25;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
                    
                    if (strength >= 100) {
                        strengthLabel = 'Très fort';
                        strengthColor = '#27ae60';
                    } else if (strength >= 75) {
                        strengthLabel = 'Fort';
                        strengthColor = '#f39c12';
                    } else if (strength >= 50) {
                        strengthLabel = 'Moyen';
                        strengthColor = '#e67e22';
                    }
                }

                strengthFill.style.width = strength + '%';
                strengthFill.style.backgroundColor = strengthColor;
                strengthText.textContent = strengthLabel;
                strengthText.style.color = strengthColor;
            });
        }

        // Validation du formulaire
        document.querySelector('.account-form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            
            if (newPassword && newPassword.length < 8) {
                e.preventDefault();
                alert('Le nouveau mot de passe doit contenir au moins 8 caractères.');
                return false;
            }
        });
    </script>
</body>
</html>
