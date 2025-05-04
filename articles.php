<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\articles.php -->
<?php
// Inclure la connexion à la base de données
include 'db_connection.php';

// Définir le nombre d'articles par page
$articlesParPage = 9;

// Récupérer le numéro de la page actuelle depuis l'URL (par défaut : 1)
$pageActuelle = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($pageActuelle < 1) {
    $pageActuelle = 1;
}

// Calculer l'offset pour la requête SQL
$offset = ($pageActuelle - 1) * $articlesParPage;

// Récupérer les catégories pour le filtre
$categories = [];
try {
    $categoriesQuery = "SELECT idCategorie, nom FROM categorie";
    $categoriesStmt = $pdo->query($categoriesQuery);
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='error-message'>Erreur lors de la récupération des catégories : " . $e->getMessage() . "</p>";
}

// Appliquer les filtres
$whereClauses = [];
$params = [];

// Filtre par catégorie
if (!empty($_GET['categorie'])) {
    $whereClauses[] = "idCategorie = :categorie";
    $params[':categorie'] = intval($_GET['categorie']);
}

// Filtre par prix minimum
if (!empty($_GET['prix_min'])) {
    $whereClauses[] = "prix >= :prix_min";
    $params[':prix_min'] = floatval($_GET['prix_min']);
}

// Filtre par prix maximum
if (!empty($_GET['prix_max'])) {
    $whereClauses[] = "prix <= :prix_max";
    $params[':prix_max'] = floatval($_GET['prix_max']);
}

// Construire la requête SQL avec les filtres
$whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
$query = "SELECT nProduit, nom, description, prix, image FROM produit $whereSQL LIMIT :offset, :limit";
$stmt = $pdo->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $articlesParPage, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total d'articles pour la pagination
$totalArticlesQuery = "SELECT COUNT(*) AS total FROM produit $whereSQL";
$totalStmt = $pdo->prepare($totalArticlesQuery);
foreach ($params as $key => $value) {
    $totalStmt->bindValue($key, $value);
}
$totalStmt->execute();
$totalArticles = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalArticles / $articlesParPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Articles</title>
    <link rel="stylesheet" href="style.css">
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
    <!-- Section des filtres -->
    <section class="filter">
        <form method="GET" action="articles.php" class="filter-form">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo htmlspecialchars($categorie['idCategorie']); ?>" <?php echo (isset($_GET['categorie']) && $_GET['categorie'] == $categorie['idCategorie']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categorie['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="prix_min">Prix minimum :</label>
            <input type="number" name="prix_min" id="prix_min" step="0.01" value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">

            <label for="prix_max">Prix maximum :</label>
            <input type="number" name="prix_max" id="prix_max" step="0.01" value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">

            <button type="submit" class="filter-button">Filtrer</button>
        </form>
    </section>

    <!-- Section des articles -->
    <section class="grid">
        <?php foreach ($articles as $article): ?>
            <a href="./FicheProduit.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="card">
                <div class="image-container">
                    <?php if (!empty($article['image'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($article['image']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                    <?php else: ?>
                        <img src="./assets/images/default.jpg" alt="Image par défaut">
                    <?php endif; ?>
                </div>
                <div class="text-content">
                    <p><strong><?php echo htmlspecialchars($article['nom']); ?></strong></p>
                    <p><?php echo htmlspecialchars($article['description']); ?></p>
                    <p><strong><?php echo htmlspecialchars($article['prix']); ?> €</strong></p>
                </div>
            </a>
        <?php endforeach; ?>
    </section>

    <!-- Section pagination -->
    <section class="pagination">
        <?php if ($pageActuelle > 1): ?>
            <a href="?page=<?php echo $pageActuelle - 1; ?>" class="pagination-button previous">&lt; Précédent</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="pagination-button <?php echo $i === $pageActuelle ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($pageActuelle < $totalPages): ?>
            <a href="?page=<?php echo $pageActuelle + 1; ?>" class="pagination-button next">Suivant &gt;</a>
        <?php endif; ?>
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