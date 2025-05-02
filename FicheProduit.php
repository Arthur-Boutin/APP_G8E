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
$query = "SELECT nom, description, prix, quantitee, image FROM produit WHERE nProduit = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}

// Gestion de l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantite = intval($_POST['quantite']);
    if ($quantite > 0) {
        // Vérifier si le produit est déjà dans le panier
        $query = "SELECT quantite FROM panierachat WHERE idProduit = :idProduit";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':idProduit' => $id]);
        $panierItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($panierItem) {
            // Si le produit est déjà dans le panier, mettre à jour la quantité
            $nouvelleQuantite = $panierItem['quantite'] + $quantite;
            $query = "UPDATE panierachat SET quantite = :quantite, dateAjoutee = :dateAjoutee WHERE idProduit = :idProduit";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':quantite' => $nouvelleQuantite,
                ':dateAjoutee' => time(),
                ':idProduit' => $id
            ]);
        } else {
            // Si le produit n'est pas dans le panier, l'ajouter
            $query = "INSERT INTO panierachat (idProduit, quantite, dateAjoutee) VALUES (:idProduit, :quantite, :dateAjoutee)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':idProduit' => $id,
                ':quantite' => $quantite,
                ':dateAjoutee' => time()
            ]);
        }

        echo "<p class='success-message'>Produit ajouté au panier avec succès !</p>";
    } else {
        echo "<p class='error-message'>Veuillez sélectionner une quantité valide.</p>";
    }
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
      <div class="logo">
        <a href="./index.html">NUTWORK</a>
      </div>
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
      <div class="product-image">
        <?php if (!empty($produit['image'])): ?>
          <img src="data:image/jpeg;base64,<?php echo base64_encode($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
        <?php else: ?>
          <img src="./assets/images/default.jpg" alt="Image par défaut">
        <?php endif; ?>
      </div>
      <div class="product-info">
        <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
        <div class="product-price"><?php echo htmlspecialchars($produit['prix']); ?> €</div>
        <div class="product-quantity">Quantité disponible : <?php echo htmlspecialchars($produit['quantitee']); ?></div>
        <p class="product-description"><?php echo htmlspecialchars($produit['description']); ?></p>
        <form action="" method="POST" class="add-to-cart-form">
          <label for="quantite">Quantité :</label>
          <input type="number" id="quantite" name="quantite" min="1" max="<?php echo htmlspecialchars($produit['quantitee']); ?>" value="1" required>
          <button type="submit" class="add-to-cart-button">Ajouter au panier</button>
        </form>
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
