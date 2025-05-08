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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantite'])) {
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

// Gestion de l'ajout d'un avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu']) && isset($_POST['note'])) {
    $contenu = htmlspecialchars($_POST['contenu']);
    $note = floatval($_POST['note']);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        echo "<p class='error-message'>Vous devez être connecté pour laisser un avis.</p>";
    } else {
        $idClient = $_SESSION['user']['idUtilisateur'];

        // Insérer le commentaire dans la base de données
        $query = "INSERT INTO commentaire (idClient, nProduit, contenu, note) VALUES (:idClient, :nProduit, :contenu, :note)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':idClient' => $idClient,
            ':nProduit' => $id,
            ':contenu' => $contenu,
            ':note' => $note
        ]);

        echo "<p class='success-message'>Votre avis a été ajouté avec succès !</p>";
    }
}

// Récupérer les avis existants
$query = "SELECT idClient, contenu, note FROM commentaire WHERE nProduit = :nProduit";
$stmt = $pdo->prepare($query);
$stmt->execute([':nProduit' => $id]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<?php include 'header.php'; ?>

  <main>
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

      <!-- Formulaire pour ajouter un avis -->
      <div class="add-review-container">
        <h3>Ajouter un avis</h3>
        <form action="" method="POST" class="add-review-form">
          <label for="contenu">Commentaire :</label>
          <textarea id="contenu" name="contenu" required></textarea>

          <label for="note">Note (sur 5) :</label>
          <input type="number" id="note" name="note" min="1" max="5" required>

          <button type="submit" class="add-review-button">Envoyer</button>
        </form>
      </div>

      <!-- Afficher les avis existants -->
      <div class="existing-reviews-container">
        <h3>Avis des utilisateurs</h3>
        <?php if (empty($avis)): ?>
          <p>Aucun avis pour le moment.</p>
        <?php else: ?>
          <?php foreach ($avis as $commentaire): ?>
            <div class="review">
              <p><strong>Utilisateur :</strong> <?php echo htmlspecialchars($commentaire['idClient']); ?></p>
              <p><strong>Note :</strong> <?php echo htmlspecialchars($commentaire['note']); ?> / 5</p>
              <p><?php echo htmlspecialchars($commentaire['contenu']); ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </main>

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
