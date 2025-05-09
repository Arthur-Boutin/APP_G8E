<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\modifier-article.php -->
<?php
// Inclure la connexion à la base de données
include 'db_connection.php';
include 'session.php';

// Vérifie si l'utilisateur est un artisan ou un administrateur
if ($_SESSION['user']['role'] !== 'artisan' && $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: index.html');
    exit();
}

// Récupère l'ID de l'artisan connecté
$idArtisan = null;

if ($_SESSION['user']['role'] === 'artisan') {
    if (isset($_SESSION['artisan']['idArtisan'])) {
        $idArtisan = $_SESSION['artisan']['idArtisan'];
    } else {
        die("Informations de l'artisan manquantes.");
    }
}

// Vérifie si un ID est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du produit manquant.");
}

$id = intval($_GET['id']);

// Récupérer les données du produit à modifier
if ($_SESSION['user']['role'] === 'administrateur') {
    $query = "SELECT nom, description, prix, quantitee, image, idArtisan, idCategorie, tempsFabrication, tailles, materiaux, couleur FROM produit WHERE nProduit = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);
} else {
    $query = "SELECT nom, description, prix, quantitee, image, idCategorie, tempsFabrication, tailles, materiaux, couleur FROM produit WHERE nProduit = :id AND idArtisan = :idArtisan";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id, ':idArtisan' => $idArtisan]);
}

$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable ou vous n'avez pas la permission de le modifier.");
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantitee = intval($_POST['quantitee']);
    $idCategorie = intval($_POST['idCategorie']);
    $tempsFabrication = intval($_POST['tempsFabrication']);
    $tailles = htmlspecialchars($_POST['tailles']);
    $materiaux = htmlspecialchars($_POST['materiaux']);
    $couleur = htmlspecialchars($_POST['couleur']);
    $imageData = $produit['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Récupérer l'ID de l'artisan
    if ($_SESSION['user']['role'] === 'administrateur') {
        $idArtisan = $_POST['idArtisan'];
    } else {
        $idArtisan = $_SESSION['artisan']['idArtisan'];
    }

    // Mettre à jour le produit
    $update_query = "UPDATE produit SET nom = :nom, description = :description, prix = :prix, quantitee = :quantitee, image = :image, idCategorie = :idCategorie, tempsFabrication = :tempsFabrication, tailles = :tailles, materiaux = :materiaux, couleur = :couleur";
    $params = [
        ':nom' => $nom,
        ':description' => $description,
        ':prix' => $prix,
        ':quantitee' => $quantitee,
        ':image' => $imageData,
        ':idCategorie' => $idCategorie,
        ':tempsFabrication' => $tempsFabrication,
        ':tailles' => $tailles,
        ':materiaux' => $materiaux,
        ':couleur' => $couleur
    ];

    // Si l'utilisateur est un administrateur, mettre à jour l'ID de l'artisan
    if ($_SESSION['user']['role'] === 'administrateur') {
        $update_query .= ", idArtisan = :idArtisan";
        $params[':idArtisan'] = $idArtisan;
    }

    $update_query .= " WHERE nProduit = :id";
    $params[':id'] = $id;

    try {
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute($params);

        header("Location: gestion-articles.php");
        exit();
    } catch (PDOException $e) {
        echo "<p class='error-message'>Erreur lors de la modification du produit : " . $e->getMessage() . "</p>";
    }
}

// Récupération des artisans pour le menu déroulant
$artisans = [];
try {
    $stmtArtisans = $pdo->query("SELECT IdArtisan, nom FROM artisan");
    $artisans = $stmtArtisans->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='error-message'>Erreur lors de la récupération des artisans : " . $e->getMessage() . "</p>";
}

// Récupération des catégories pour le menu déroulant
$categories = [];
try {
    $stmtCategories = $pdo->query("SELECT idCategorie, nom FROM categorie");
    $categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='error-message'>Erreur lors de la récupération des catégories : " . $e->getMessage() . "</p>";
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

                <label for="tempsFabrication">Temps de Fabrication (jours) :</label>
                <input type="number" id="tempsFabrication" name="tempsFabrication" min="1" value="<?php echo htmlspecialchars($produit['tempsFabrication']); ?>" required>

                <?php if ($_SESSION['user']['role'] === 'administrateur'): ?>
                    <label for="idArtisan">Artisan</label>
                    <select id="idArtisan" name="idArtisan" required>
                        <option value="">Sélectionnez un artisan</option>
                        <?php foreach ($artisans as $artisan): ?>
                            <option value="<?php echo htmlspecialchars($artisan['IdArtisan']); ?>" <?php if (isset($produit['idArtisan']) && $produit['idArtisan'] == $artisan['IdArtisan']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($artisan['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <label for="idCategorie">Catégorie</label>
                <select id="idCategorie" name="idCategorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo htmlspecialchars($categorie['idCategorie']); ?>" <?php if ($produit['idCategorie'] == $categorie['idCategorie']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($categorie['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="tailles">Tailles possibles (séparées par des virgules)</label>
                <input type="text" id="tailles" name="tailles" value="<?php echo htmlspecialchars($produit['tailles']); ?>">

                <label for="materiaux">Matériaux possibles (séparés par des virgules)</label>
                <input type="text" id="materiaux" name="materiaux" value="<?php echo htmlspecialchars($produit['materiaux']); ?>">

                <label for="couleur">Couleurs possibles (séparées par des virgules)</label>
                <input type="text" id="couleur" name="couleur" value="<?php echo htmlspecialchars($produit['couleur']); ?>">

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
    <?php include 'footer.php'; ?>
</body>
</html>