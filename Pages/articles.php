<?php
ob_start();
include 'header.php';
include __DIR__ . '/../setup/db_connection.php';

$articlesParPage = 9;
$pageActuelle = isset($_GET['page']) ? intval($_GET['page']) : 1;
$pageActuelle = max($pageActuelle, 1);
$offset = ($pageActuelle - 1) * $articlesParPage;

$categories = [];
try {
    $categoriesQuery = "SELECT idCategorie, nom FROM categorie";
    $categoriesStmt = $pdo->query($categoriesQuery);
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='error-message'>Erreur lors de la récupération des catégories : " . $e->getMessage() . "</p>";
}

function getArticles($pdo, $whereClauses = [], $params = [], $limit = null, $offset = null, $categorie = null) {
    $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
    $limitSQL = ($limit !== null && $offset !== null) ? "LIMIT :offset, :limit" : "";

    if ($categorie === 'populaires') {
        $whereSQL .= (!empty($whereSQL) ? " AND " : " WHERE ") . " prix > 100";
    } elseif ($categorie === 'recents') {
        $whereSQL .= (!empty($whereSQL) ? " AND " : " WHERE ") . " dateAjout > DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    } elseif ($categorie === 'mieuxnotes') {
        $whereSQL .= (!empty($whereSQL) ? " AND " : " WHERE ") . " prix < 50";
    }

    $query = "SELECT p.nProduit, p.nom, p.description, p.prix, p.image, AVG(c.note) AS note_moyenne
              FROM produit p
              LEFT JOIN commentaire c ON p.nProduit = c.nProduit
              $whereSQL
              GROUP BY p.nProduit
              $limitSQL";

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

$articlesPopulaires = getArticles($pdo, [], [], 10, 0);
$articlesRecents = getArticles($pdo, [], [], 10, 0);
$articlesMieuxNotes = getArticles($pdo, [], [], 10, 0);

$whereClauses = [];
$params = [];

if (!empty($_GET['categorie'])) {
    $whereClauses[] = "idCategorie = :categorie";
    $params[':categorie'] = intval($_GET['categorie']);
}

if (!empty($_GET['prix_min'])) {
    $whereClauses[] = "prix >= :prix_min";
    $params[':prix_min'] = floatval($_GET['prix_min']);
}

if (!empty($_GET['prix_max'])) {
    $whereClauses[] = "prix <= :prix_max";
    $params[':prix_max'] = floatval($_GET['prix_max']);
}

$articles = getArticles($pdo, $whereClauses, $params, $articlesParPage, $offset);

$whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
$totalArticlesQuery = "SELECT COUNT(*) AS total FROM produit $whereSQL";
$totalStmt = $pdo->prepare($totalArticlesQuery);
foreach ($params as $key => $value) {
    $totalStmt->bindValue($key, $value);
}
$totalStmt->execute();
$totalArticles = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalPages = ceil($totalArticles / $articlesParPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Articles</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<main>
    <section class="filter">
        <h2>Filtrer les articles</h2>
        <form method="GET" action="articles.php" class="filter-form">
            <label for="categorie">Catégorie:</label>
            <select name="categorie" id="categorie">
                <option value="">Toutes</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo htmlspecialchars($categorie['idCategorie']); ?>" <?php echo (isset($_GET['categorie']) && $_GET['categorie'] == $categorie['idCategorie']) ? 'selected' : ''; ?>" class="categorie-option">
                        <?php echo htmlspecialchars($categorie['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="prix_min">Prix Min:</label>
            <input type="number" name="prix_min" id="prix_min" step="0.01" value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">

            <label for="prix_max">Prix Max:</label>
            <input type="number" name="prix_max" id="prix_max" step="0.01" value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">

            <button type="submit" class="pagination-button">Filtrer</button>
        </form>
    </section>
    <section class="article-section popular-articles">
        <h2>Articles Populaires</h2>
        <div class="scrollable-container">
            <?php foreach ($articlesPopulaires as $article): ?>
                <a href="./FicheProduit.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="card">
                    <div class="image-container">
                        <?php if (!empty($article['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($article['image']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <?php else: ?>
                            <img src="../assets/images/default.jpg" alt="Image par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="text-content">
                        <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                        <p class="price"><?php echo htmlspecialchars($article['prix']); ?> €</p>
                        <?php if ($article['note_moyenne'] !== null): ?>
                            <p>
                                <?php
                                $note = round($article['note_moyenne']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $note) {
                                        echo '<span style="color: gold;">★</span>';
                                    } else {
                                        echo '<span style="color: lightgray;">☆</span>';
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="article-section recent-articles">
        <h2>Articles Récents</h2>
        <div class="scrollable-container">
            <?php foreach ($articlesRecents as $article): ?>
                <a href="./FicheProduit.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="card">
                    <div class="image-container">
                        <?php if (!empty($article['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($article['image']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <?php else: ?>
                            <img src="../assets/images/default.jpg" alt="Image par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="text-content">
                        <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                        <p class="price"><?php echo htmlspecialchars($article['prix']); ?> €</p>
                        <?php if ($article['note_moyenne'] !== null): ?>
                            <p>
                                <?php
                                $note = round($article['note_moyenne']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $note) {
                                        echo '<span style="color: gold;">★</span>';
                                    } else {
                                        echo '<span style="color: lightgray;">☆</span>';
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

   
    <section class="article-section top-rated-articles">
        <h2>Articles les Mieux Notés</h2>
        <div class="scrollable-container">
            <?php foreach ($articlesMieuxNotes as $article): ?>
                <a href="./FicheProduit.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="card">
                    <div class="image-container">
                        <?php if (!empty($article['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($article['image']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <?php else: ?>
                            <img src="../assets/images/default.jpg" alt="Image par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="text-content">
                        <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                        <p class="price"><?php echo htmlspecialchars($article['prix']); ?> €</p>
                        <?php if ($article['note_moyenne'] !== null): ?>
                            <p>
                                <?php
                                $note = round($article['note_moyenne']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $note) {
                                        echo '<span style="color: gold;">★</span>';
                                    } else {
                                        echo '<span style="color: lightgray;">☆</span>';
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="article-section">
        <h2>Tous les Articles</h2>
        <div class="grid">
            <?php foreach ($articles as $article): ?>
                <a href="./FicheProduit.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="card">
                    <div class="image-container">
                        <?php if (!empty($article['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($article['image']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <?php else: ?>
                            <img src="../assets/images/default.jpg" alt="Image par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="text-content">
                        <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                        <p class="price"><?php echo htmlspecialchars($article['prix']); ?> €</p>
                        <?php if ($article['note_moyenne'] !== null): ?>
                            <p>
                                <?php
                                $note = round($article['note_moyenne']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $note) {
                                        echo '<span style="color: gold;">★</span>';
                                    } else {
                                        echo '<span style="color: lightgray;">☆</span>';
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>


    <section class="pagination">
        <?php if ($pageActuelle > 1): ?>
            <a href="?page=<?php echo $pageActuelle - 1; ?>" aria-label="Previous"  class="pagination-button">
                <i class="fas fa-chevron-left"></i>
            </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="pagination-button <?php echo $i === $pageActuelle ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($pageActuelle < $totalPages): ?>
            <a href="?page=<?php echo $pageActuelle + 1; ?>" aria-label="Next" class="pagination-button">
                <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </section>
</main>

<?php include 'footer.php'; ?>
<?php ob_end_flush(); ?>
</body>
</html>