<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$idUtilisateur = $_SESSION['user']['idUtilisateur'];
$role = $_SESSION['user']['role'];
$error = '';
$success = '';
$userData = [];

// Récupérer les informations de l'utilisateur connecté
try {
    if ($role === 'artisan') {
        $query = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, 
                         artisan.nom, artisan.adresse, artisan.email 
                  FROM utilisateur 
                  INNER JOIN artisan ON utilisateur.idUtilisateur = artisan.idArtisan 
                  WHERE utilisateur.idUtilisateur = :idUtilisateur";
    } else {
        $query = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, 
                         client.adresse, client.email 
                  FROM utilisateur 
                  INNER JOIN client ON utilisateur.idUtilisateur = client.idClient 
                  WHERE utilisateur.idUtilisateur = :idUtilisateur";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute([':idUtilisateur' => $idUtilisateur]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des informations : " . $e->getMessage();
}

// Mettre à jour les informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $adresse = $_POST['adresse'] ?? null;
    $email = $_POST['email'] ?? null;

    try {
        if ($role === 'artisan') {
            $updateQuery = "UPDATE artisan 
                            SET nom = :nom, adresse = :adresse, email = :email 
                            WHERE idArtisan = :idUtilisateur";
        } else {
            $updateQuery = "UPDATE client 
                            SET adresse = :adresse, email = :email 
                            WHERE idClient = :idUtilisateur";
        }

        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([
            ':nom' => $nom,
            ':adresse' => $adresse,
            ':email' => $email,
            ':idUtilisateur' => $idUtilisateur
        ]);

        $success = "Vos informations ont été mises à jour avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil – Nutwork</title>
  <link rel="stylesheet" href="./style.css">

  <!-- correctifs locaux ----------------------------------------------------->  
  <style>
    /* === SECTION PARAMÈTRE =============================================== */
    .param-form{display:flex;flex-direction:column;gap:18px}
    .param-row{display:flex;align-items:center;gap:14px}
    .param-row label{min-width:220px;font-weight:600;color:#5d3e1b}

    /* tous les champs ont la même largeur car un “emplacement-bouton” est
       présent sur chaque ligne : réel ou invisible                         */
    .param-row input{
      flex:1 1 auto;padding:8px 10px;border:1px solid #ccc;border-radius:4px}
    .btn-inline,
    .btn-placeholder{
      flex:0 0 120px;border-radius:4px;padding:8px 20px;text-align:center}
    .btn-inline{
      background:#8b5a2b;color:#fff;border:none;cursor:pointer;white-space:nowrap}
    .btn-inline:hover{filter:brightness(1.08)}
    .btn-placeholder{visibility:hidden}      /* occupe la place du bouton */

    /* on masque l’ancien bouton global                                      */
    .btn-update{display:none}
  </style>
</head>

<body>
<!-- ===========================  HEADER  ================================== -->
<?php include 'header.php'; ?>

<!-- ===========================  MAIN  ==================================== -->
<main>
  <h1 class="page-title">Espace Personnel</h1>

  <!-- Affichage des messages d'erreur ou de succès -->
  <?php if (!empty($error)): ?>
    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
  <?php elseif (!empty($success)): ?>
    <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
  <?php endif; ?>

  <!-- PROFIL + INFORMATION -------------------------------------------------->
  <section class="profile-section">
    <div class="profile-block">
      <h2 class="block-title">PROFIL</h2>
      <div class="profile-photo">
        <img src="./assets/images/LOGO.png" alt="Photo de profil">
        <p class="photo-label">Photo actuelle</p>
        <button class="btn btn-upload">Changer la photo</button>
      </div>
    </div>

    <div class="info-block">
      <h2 class="block-title">INFORMATION</h2>
      <form class="info-form" action="" method="POST">
        <?php if ($role === 'artisan'): ?>
          <label>NOM :</label>
          <input name="nom" value="<?php echo htmlspecialchars($userData['nom'] ?? ''); ?>" placeholder="Entrez votre nom">
        <?php endif; ?>
        <label>EMAIL :</label>
        <input name="email" type="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" placeholder="Entrez votre email">
        <label>ADRESSE :</label>
        <input name="adresse" value="<?php echo htmlspecialchars($userData['adresse'] ?? ''); ?>" placeholder="Entrez votre adresse">
        <button class="btn btn-save" type="submit">Enregistrer</button>
      </form>
    </div>
  </section>

  <!-- PARAMÈTRE ------------------------------------------------------------->  
  <section class="param-section">
    <h2 class="param-title">PARAMÈTRE</h2>

    <form class="param-form">
      <!-- définir mot de passe -->
      <div class="param-row">
        <label for="new-pwd">Définir un mot de passe :</label>
        <input id="new-pwd" type="password">
        <span class="btn-placeholder"></span>
      </div>

      <div class="param-row">
        <label for="new-pwd-confirm">Confirmer le mot de passe :</label>
        <input id="new-pwd-confirm" type="password">
        <button class="btn-inline" type="button">Mettre à jour</button>
      </div>

      <!-- changer mot de passe -->
      <div class="param-row">
        <label for="change-pwd">Changer le mot de passe :</label>
        <input id="change-pwd" type="password">
        <span class="btn-placeholder"></span>
      </div>

      <div class="param-row">
        <label for="change-pwd-confirm">Confirmer le mot de passe :</label>
        <input id="change-pwd-confirm" type="password">
        <button class="btn-inline" type="button">Mettre à jour</button>
      </div>
    </form>
  </section>
</main>

<!-- ===========================  FOOTER  ================================== -->
<footer class="site-footer">
  <div>
    <h4>À propos de Nutwork</h4>
    <p><a href="./contact.html">Contactez-nous</a></p>
    <p><a href="./.html">À propos de nous</a></p>
    <p><a href="./contact.html">Blog</a></p>
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
<script src="./script.js"></script>
</body>
</html>