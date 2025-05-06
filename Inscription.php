<?php
require 'db_connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'client'; // Par défaut, le rôle est "client"

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        try {
            // Vérifier si l'email existe déjà dans la table utilisateur
            $checkEmailQuery = ($role === 'artisan') 
                ? "SELECT email FROM artisan WHERE email = :email" 
                : "SELECT email FROM client WHERE email = :email";

            $stmtCheckEmail = $pdo->prepare($checkEmailQuery);
            $stmtCheckEmail->execute([':email' => $email]);
            $existingUser = $stmtCheckEmail->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $error = 'Un compte avec cette adresse email existe déjà.';
            } else {
                // Générer un ID unique pour l'utilisateur
                $idUtilisateur = uniqid('U');

                // Hash du mot de passe
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insérer dans la table utilisateur
                $sqlUtilisateur = "INSERT INTO utilisateur (idUtilisateur, mdp, statutConnexion, dateInscription) 
                                    VALUES (:idUtilisateur, :mdp, 'actif', UNIX_TIMESTAMP())";
                $stmtUtilisateur = $pdo->prepare($sqlUtilisateur);
                $stmtUtilisateur->execute([
                    ':idUtilisateur' => $idUtilisateur,
                    ':mdp' => $password_hash
                ]);

                // Insérer dans la table artisan ou client
                if ($role === 'artisan') {
                    $sqlArtisan = "INSERT INTO artisan (idArtisan, nom, email) 
                                   VALUES (:idArtisan, :nom, :email)";
                    $stmtArtisan = $pdo->prepare($sqlArtisan);
                    $stmtArtisan->execute([
                        ':idArtisan' => $idUtilisateur, // L'ID artisan est le même que l'ID utilisateur
                        ':nom' => $username,
                        ':email' => $email
                    ]);
                } elseif ($role === 'client') {
                    $sqlClient = "INSERT INTO client (idClient, adresse, email) 
                                  VALUES (:idClient, NULL, :email)";
                    $stmtClient = $pdo->prepare($sqlClient);
                    $stmtClient->execute([
                        ':idClient' => $idUtilisateur, // L'ID client est le même que l'ID utilisateur
                        ':email' => $email
                    ]);
                }

                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - Nutwork</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <header class="site-header">
    <div class="header-container">
      <!-- Logo -->
      <div class="logo">
        <a href="./index.html">NUTWORK</a>
      </div>
  
      <!-- Navigation -->
      <nav class="nav-menu">
        <ul>
          <li><a href="./index.html">Accueil</a></li>
          <li><a href="./articles.php">Articles</a></li>
          <li><a href="#">Galerie</a></li>
          <li><a href="./contact.html">Contact</a></li>
        </ul>
      </nav>
  
      <!-- Actions -->
      <div class="header-actions">
        <form class="search-form">
          <input type="text" name="rechercher" class="search-bar" placeholder="Rechercher...">
          <button type="submit" class="search-button">🔍</button>
        </form>
        <a href="./messagerie.html" class="icon-link">
          <img src="./assets/images/Mail.png" alt="Messagerie" class="icon">
        </a>
        <a href="./panier.html" class="icon-link">
          <img src="./assets/images/truc.png" alt="Panier" class="icon">
        </a>
        <a href="./login.html" class="icon-link">
          <img src="./assets/images/Profil.png" alt="Profil" class="icon">
        </a>
      </div>
    </div>
  </header>

  <main>
    <section class="register-section">
      <div class="register-container">
        <h1>Inscription</h1>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (!empty($error)): ?>
          <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (!empty($success)): ?>
          <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <!-- Boutons de sélection Artisan/Client -->
        <div class="user-type-selection">
          <button type="button" id="artisan-btn" class="user-type-button active" onclick="selectRole('artisan')">Artisan</button>
          <button type="button" id="client-btn" class="user-type-button" onclick="selectRole('client')">Client</button>
        </div>

        <form action="" method="POST">
          <input type="hidden" id="role" name="role" value="artisan">

          <div class="register-box">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" placeholder="Votre nom d’utilisateur" required>

            <label for="email">Adresse mail</label>
            <input type="email" id="email" name="email" placeholder="Votre adresse email" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>

           <label for="confirm_password">Confirmez le mot de passe</label>
           <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>

          <div class="terms-checkbox">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">J’accepte les Conditions Générales d’Utilisation</label>
          </div>

          <button type="submit" class="btn">Créer un compte</button>
        </form>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div>
        <h4>À propos de Nutwork</h4>
        <p><a href="./contact.html">Contactez-nous</a></p>
        <p>À propos de nous</p>
        <p>Blog</p>
        <p><a href="./faq.html">FAQ</a></p>
    </div>
    <div>
        <h4>CGU</h4>
        <p><a href="./Mentions.html">Mentions</a></p>
        <p><a href="./cgv.html">CGV</a></p>
        <p>Développement</p>
    </div>
    <div>
        <h4>Aide & Contacts</h4>
        <p>contact@nutwork.com</p>
        <p>28 Rue Notre Dame des Champs, Paris</p>
    </div>
  </footer>

  <script>
    function selectRole(role) {
      document.getElementById('role').value = role;
      document.getElementById('artisan-btn').classList.toggle('active', role === 'artisan');
      document.getElementById('client-btn').classList.toggle('active', role === 'client');
    }
  </script>
</body>
</html>
