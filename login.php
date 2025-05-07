<?php
require 'db_connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'artisan'; // Par défaut, le rôle est "artisan"

    if (empty($email) || empty($password)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        try {
            // Vérifier si l'utilisateur est un artisan ou un client
            if ($role === 'artisan') {
                // Requête pour récupérer les informations de l'artisan
                $query = "SELECT utilisateur.mdp, utilisateur.idUtilisateur, utilisateur.role, artisan.nom 
                          FROM utilisateur 
                          INNER JOIN artisan ON utilisateur.idUtilisateur = artisan.idArtisan 
                          WHERE artisan.email = :email";
            } else {
                // Requête pour récupérer les informations du client
                $query = "SELECT utilisateur.mdp, utilisateur.idUtilisateur, utilisateur.role, client.adresse 
                          FROM utilisateur 
                          INNER JOIN client ON utilisateur.idUtilisateur = client.idClient 
                          WHERE client.email = :email";
            }

            $stmt = $pdo->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mdp'])) {
                // Démarrer la session et stocker les informations utilisateur
                session_start();
                $_SESSION['idUtilisateur'] = $user['idUtilisateur'];
                $_SESSION['user'] = [
                    'idUtilisateur' => $user['idUtilisateur'],
                    'role' => $user['role'], // Ajout du rôle
                ];

                // Rediriger vers la page de gestion des articles ou autre
                header('Location: profil.php');
                exit();
            } else {
                $error = 'Adresse email ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion au compte - Nutwork</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="login-container">
    <h1>Connexion au compte</h1>

    <!-- Affichage des messages d'erreur -->
    <?php if (!empty($error)): ?>
      <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Boutons de sélection Artisan/Client -->
    <div class="user-type-selection">
      <button type="button" id="artisan-btn" class="user-type-button active" onclick="selectRole('artisan')">Artisan</button>
      <button type="button" id="client-btn" class="user-type-button" onclick="selectRole('client')">Client</button>
    </div>

    <form action="" method="POST">
      <input type="hidden" id="role" name="role" value="artisan">

      <div class="login-box">
        <label for="email">Adresse mail</label>
        <input type="email" id="email" name="email" placeholder="Votre adresse email" required>

        <label for="password">Mot de Passe</label>
        <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>

        <div class="login-buttons">
          <button type="submit" class="btn">Confirmer</button>
          <a href="./Inscription.php" class="btn">Inscription</a>
          <a href="./mdpoublie.php" class="btn">Mot de passe oublié ?</a>
        </div>
      </div>
    </form>
  </div>

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