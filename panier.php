<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Accueil</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<?php
include
$host = 'localhost';
$dbname = 'app_g8e'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur par défaut pour phpMyAdmin
$password = ''; // Mot de passe par défaut pour phpMyAdmin

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
    <!-- Header intégré -->
    <?php include 'header.php'; ?>
  <main>
    <div class="cart-container">
      <div class="cart-title-bar">Mon Panier</div>

      <div class="cart-item">
        <div class="cart-item-info">
          <h3>Produit 1</h3>
          <p>Prix: 30€</p>
        </div>
        <button class="btn-remove">Supprimer</button>
      </div>

      <div class="cart-item">
        <div class="cart-item-info">
          <h3>Produit 2</h3>
          <p>Prix: 50€</p>
        </div>
        <button class="btn-remove">Supprimer</button>
      </div>

      <div class="cart-item">
        <div class="cart-item-info">
          <h3>Produit 3</h3>
          <p>Prix: 70€</p>
        </div>
        <button class="btn-remove">Supprimer</button>
      </div>

      <div class="cart-summary">
        <p>Résumé du panier: Total 150€</p>
        <a href="./Paiement.php" class="btn-validate">Valider</a>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
