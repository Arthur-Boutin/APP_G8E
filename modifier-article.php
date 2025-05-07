<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\modifier-article.php -->
<?php
// Inclure la connexion à la base de données
include 'db_connection.php';
include 'session.php';

// Vérifie si l'utilisateur est un artisan
if ($_SESSION['user']['role'] !== 'artisan') {
    header('Location: index.html');
    exit();
}

// Récupère l'ID de l'artisan connecté
$idArtisan = $_SESSION['artisan']['idArtisan'];

// Vérifie si un ID est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du produit manquant.");
}

$id = intval($_GET['id']);

// Récupérer les données du produit à modifier
$query = "SELECT nom, description, prix, quantitee, image FROM produit WHERE nProduit = :id AND idArtisan = :idArtisan";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id, ':idArtisan' => $idArtisan]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable ou vous n'avez pas la permission de le modifier.");
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $quantitee = $_POST['quantitee'];
    $image = $produit['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Vérifier que l'article appartient bien à l'artisan connecté
    $check_query = "SELECT nProduit FROM produit WHERE nProduit = :id AND idArtisan = :idArtisan";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->execute([':id' => $id, ':idArtisan' => $idArtisan]);
    if (!$check_stmt->fetch()) {
        die("Vous n'avez pas la permission de modifier cet article.");
    }

    // Mettre à jour le produit
    $update_query = "UPDATE produit SET nom = :nom, description = :description, prix = :prix, quantitee = :quantitee, image = :image WHERE nProduit = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->execute([
        ':nom' => $nom,
        ':description' => $description,
        ':prix' => $prix,
        ':quantitee' => $quantitee,
        ':image' => $image,
        ':id' => $id
    ]);

    header("Location: gestion-articles.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Header intégré -->
    <?php include 'header.php'; ?>

    <main>
        <section class="create-article-container">
            <div class="create-article-header">
                <h1>Modifier un produit</h1>
            </div>
            <form class="create-article-form" method="POST" action="" enctype="multipart/form-data">
                <label for="nom">Nom de l'article</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($produit['description']); ?></textarea>

                <label for="prix">Prix</label>
                <input type="number" step="0.01" id="prix" name="prix" value="<?php echo htmlspecialchars($produit['prix']); ?>" required>

                <label for="quantitee">Quantité</label>
                <input type="number" id="quantitee" name="quantitee" value="<?php echo htmlspecialchars($produit['quantitee']); ?>" required>

                <label for="image">Image actuelle</label>
                <?php if (!empty($produit['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($produit['image']); ?>" alt="Image du produit" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">
                <?php else: ?>
                    <p>Aucune image disponible</p>
                <?php endif; ?>

                <label for="image">Nouvelle image (facultatif)</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg">

                <button type="submit" class="add-to-cart-button">Mettre à jour</button>
            </form>
        </section>
    </main>

    <!-- Footer intégré -->
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