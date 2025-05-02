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

// Récupérer les articles pour la page actuelle
$query = "SELECT nProduit, nom, description, prix, image FROM produit LIMIT :offset, :limit";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $articlesParPage, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total d'articles pour la pagination
$totalArticlesQuery = "SELECT COUNT(*) AS total FROM produit";
$totalStmt = $pdo->query($totalArticlesQuery);
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

    <main>
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