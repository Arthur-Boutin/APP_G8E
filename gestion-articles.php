<?php
session_start();
include 'db_connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$isAdmin = $_SESSION['isAdmin'] ?? false;

try {
    if ($isAdmin) {
        // Si l'utilisateur est un administrateur, récupère tous les articles
        $query = "SELECT produit.*, artisan.nom AS nomArtisan 
                  FROM produit 
                  INNER JOIN artisan ON produit.idArtisan = artisan.idArtisan";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    } else {
        // Sinon, récupère uniquement les articles de l'artisan connecté
        $idArtisan = $user['idUtilisateur'];
        $query = "SELECT * FROM produit WHERE idArtisan = :idArtisan";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':idArtisan' => $idArtisan]);
    }

    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des articles : " . $e->getMessage());
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
    <?php include 'header.php'; ?>

    <main>
        <section class="gestion-articles">
            <div class="articles-container">
                <div class="articles-header">
                    <h1>Gestion des articles</h1>
                    <a href="./creationarticles.php" class="add-article-button">Ajouter un Article</a>
                </div>
                <table class="articles-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Artisan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($article['nProduit']); ?></td>
                                    <td><?php echo htmlspecialchars($article['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($article['description']); ?></td>
                                    <td><?php echo htmlspecialchars($article['prix']); ?> €</td>
                                    <td>
                                        <?php echo $isAdmin ? htmlspecialchars($article['nomArtisan']) : 'Vous'; ?>
                                    </td>
                                    <td>
                                        <a href="modifier-article.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="edit-button">Modifier</a>
                                        <a href="supprimer-article.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Aucun article trouvé.</td>
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