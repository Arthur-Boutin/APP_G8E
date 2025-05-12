<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\articles.php -->
<?php
// Inclure la connexion à la base de données
include 'db_connection.php';

// Définir le nombre d'articles par page pour la navigation normale
$articlesParPage = 9;

// Récupérer le numéro de la page actuelle depuis l'URL (par défaut : 1)
$pageActuelle = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($pageActuelle < 1) {
    $pageActuelle = 1;
}

// Calculer l'offset pour la requête SQL
$offset = ($pageActuelle - 1) * $articlesParPage;

// Récupérer les catégories pour le filtre et les sections
$categories = [];
try {
    $categoriesQuery = "SELECT idCategorie, nom FROM categorie";
    $categoriesStmt = $pdo->query($categoriesQuery);
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='error-message'>Erreur lors de la récupération des catégories : " . $e->getMessage() . "</p>";
}

// Fonction pour récupérer les articles avec des filtres et une limite
function getArticles($pdo, $whereClauses = [], $params = [], $limit = null, $offset = null) {
    $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
    $limitSQL = ($limit !== null && $offset !== null) ? "LIMIT :offset, :limit" : "";

    $query = "SELECT nProduit, nom, description, prix, image FROM produit $whereSQL $limitSQL";
    $stmt = $pdo->prepare($query);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    if ($limit !== null && $offset !== null) {
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les articles les plus populaires (par exemple, les plus chers)
$articlesPopulaires = getArticles($pdo, [], [], 10, 0);

// Récupérer les articles les plus récents (par exemple, les 5 derniers ajoutés)
$articlesRecents = getArticles($pdo, [], [], 10, 0);

// Récupérer les articles les mieux notés (vous devrez avoir un système de notation)
// Pour cet exemple, on va simuler en récupérant les articles avec un prix élevé
$articlesMieuxNotes = getArticles($pdo, [], [], 10, 0);

// Appliquer les filtres pour la navigation normale
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

// Récupérer les articles pour la navigation normale
$articles = getArticles($pdo, $whereClauses, $params, $articlesParPage, $offset);

// Récupérer le nombre total d'articles pour la pagination
$whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
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
    <style>
        /* Styles pour les sections d'articles */
        .article-section {
            margin-bottom: 30px;
        }

        .article-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        /* Styles pour le conteneur défilable */
        .scrollable-container {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px; /* Espace pour l'ombre */
        }

        /* Masquer la barre de défilement par défaut */
        .scrollable-container::-webkit-scrollbar {
            height: 5px; /* Hauteur de la barre de défilement */
        }

        /* Style de la barre de défilement */
        .scrollable-container::-webkit-scrollbar-track {
            background: #f1f1f1; /* Couleur de fond de la barre */
            border-radius: 5px;
        }

        /* Style du curseur de la barre de défilement */
        .scrollable-container::-webkit-scrollbar-thumb {
            background: #888; /* Couleur du curseur */
            border-radius: 5px;
        }

        /* Style du curseur au survol */
        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background: #555; /* Couleur du curseur au survol */
        }

        .card {
            display: inline-block;
            width: 250px;
            flex-direction: column;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            text-decoration: none;
            color: #333;
            margin-right: 20px; /* Espacement entre les cartes */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            height: 200px;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .text-content {
            padding: 20px;
        }

        .text-content p {
            margin-bottom: 8px;
        }

        .text-content strong {
            font-weight: bold;
        }

        /* Styles pour la pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination-button {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .pagination-button:hover {
            background-color: #ddd;
        }

        .pagination-button.active {
            background-color: #007bff;
            color: #fff;
        }

        .pagination-button.previous,
        .pagination-button.next {
            background-color: #007bff;
            color: #fff;
        }

        .pagination-button.previous:hover,
        .pagination-button.next:hover {
            background-color: #0056b3;
        }

        /* Styles pour le filtre */
        .filter {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filter-form label {
            margin-right: 5px;
            font-weight: bold;
        }

        .filter-form input[type="number"],
        .filter-form select {
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            width: 150px;
        }

        .filter-form .filter-button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-form .filter-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

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

    <!-- Section des articles populaires -->
    <section class="article-section popular-articles">
        <h2>Articles Populaires</h2>
        <div class="scrollable-container">
            <?php foreach ($articlesPopulaires as $article): ?>
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
        </div>
    </section>

    <!-- Section des articles récents -->
    <section class="article-section recent-articles">
        <h2>Articles Récents</h2>
        <div class="scrollable-container">
            <?php foreach ($articlesRecents as $article): ?>
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
        </div>
    </section>

    <!-- Section des articles les mieux notés -->
    <section class="article-section top-rated-articles">
        <h2>Articles les Mieux Notés</h2>
        <div class="scrollable-container">
            <?php foreach ($articlesMieuxNotes as $article): ?>
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
        </div>
    </section>

    <!-- Section des articles (navigation normale) -->
    <section class="article-section">
        <h2>Tous les Articles</h2>
        <div class="grid">
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
        </div>
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

<?php include 'footer.php'; ?>
</body>
</html>