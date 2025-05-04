<?php
// Inclure la connexion à la base de données
include 'db_connection.php';

// Vérifier si une action de suppression a été demandée
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    try {
        $delete_query = "DELETE FROM produit WHERE nProduit = :id"; // Supprimer l'article de la table produit
        $stmt = $pdo->prepare($delete_query);
        $stmt->execute([':id' => $delete_id]);
        header("Location: gestion-articles.php"); // Rediriger après suppression
        exit();
    } catch (PDOException $e) {
        echo "<p class='error-message'>Erreur lors de la suppression : " . $e->getMessage() . "</p>";
    }
}

// Récupérer les articles depuis la table produit
$articles = [];
try {
    $query = "SELECT nProduit, nom, quantitee, prix FROM produit";
    $stmt = $pdo->query($query);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='error-message'>Erreur lors de la récupération des articles : " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Gestion des produits</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Header intégré -->
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
        <section class="gestion-articles">
            <div class="articles-container">
                <div class="articles-header">
                    <h1>Gestion des Articles</h1>
                    <a href="./creationarticles.php" class="add-article-button">Ajouter un Article</a>
                </div>
                <table class="articles-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Quantité Disponible</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($article['nProduit']); ?></td>
                                    <td><?php echo htmlspecialchars($article['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($article['quantitee']); ?></td>
                                    <td><?php echo htmlspecialchars($article['prix']); ?> €</td>
                                    <td>
                                        <a href="./modifier-article.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="edit-button">Modifier</a>
                                        <a href="?delete_id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Aucun article trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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