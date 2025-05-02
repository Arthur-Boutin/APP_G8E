<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\FicheProduit.php -->
<?php
// Inclure la connexion à la base de données
include 'db_connection.php';

// Vérifier si un ID est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du produit manquant.");
}

$id = intval($_GET['id']);

// Récupérer les données du produit depuis la base de données
$query = "SELECT nom, description, prix, quantitee FROM produit WHERE nProduit = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($produit['nom']); ?> - Nutwork</title>
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
          <li><a href="./galerie.html">Galerie</a></li>
          <li><a href="./contact.html">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="product-container">
    <div class="product-top">
      <div class="product-image">Image du produit</div>
      <div class="product-info">
        <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
        <div class="product-price"><?php echo htmlspecialchars($produit['prix']); ?> €</div>
        <div class="product-quantity">Quantité disponible : <?php echo htmlspecialchars($produit['quantitee']); ?></div>
        <p class="product-description"><?php echo htmlspecialchars($produit['description']); ?></p>
      </div>
    </div>

    <div class="reviews-section">
      <h3>Derniers avis</h3>
      <div class="review-cards">
        <div class="review-card">
          <h4>Titre de l'avis</h4>
          <p>Contenu de l'avis</p>
          <p><strong>Nom de l'auteur</strong><br>Date</p>
        </div>
        <div class="review-card">
          <h4>Titre de l'avis</h4>
          <p>Contenu de l'avis</p>
          <p><strong>Nom de l'auteur</strong><br>Date</p>
        </div>
        <div class="review-card">
          <h4>Titre de l'avis</h4>
          <p>Contenu de l'avis</p>
          <p><strong>Nom de l'auteur</strong><br>Date</p>
        </div>
      </div>
    </div>
  </div>

  <footer class="site-footer">
    <div>
        <h4>À propos de Nutwork</h4>
        <p><a href="./contact.html">Contactez-nous</a></p>
        <p>À propos de nous</p>
        <p>Blog</p>
        <p>FAQ</p>
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
</body>
</html>
