<?php
session_start();
include 'db_connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$isAdmin = ($_SESSION['user']['role'] === 'administrateur');

// Suppression d'article
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);

    try {
        // Si non admin, vérifier que l'article appartient à l'utilisateur
        if (!$isAdmin) {
            $stmt = $pdo->prepare("SELECT idArtisan FROM produit WHERE nProduit = :id");
            $stmt->execute([':id' => $deleteId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || $row['idArtisan'] != $user['idUtilisateur']) {
                echo "<script>alert('Suppression non autorisée.');window.location='gestion-articles.php';</script>";
                exit();
            }
        }

        // Suppression
        $stmt = $pdo->prepare("DELETE FROM produit WHERE nProduit = :id");
        $stmt->execute([':id' => $deleteId]);
        echo "<script>alert('Article supprimé avec succès.');window.location='gestion-articles.php';</script>";
        exit();
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de la suppression : " . addslashes($e->getMessage()) . "');window.location='gestion-articles.php';</script>";
        exit();
    }
}

// Récupération des articles
try {
    if ($isAdmin) {
        $query = "SELECT produit.*, artisan.nom AS nomArtisan 
                  FROM produit 
                  INNER JOIN artisan ON produit.idArtisan = artisan.idArtisan";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    } else {
        $idArtisan = $user['idUtilisateur'];
        $query = "SELECT produit.* FROM produit WHERE idArtisan = :idArtisan";
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
    <style>
        .actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
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
                                    <td class="actions">
                                        <a href="modifier-article.php?id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="edit-button">Modifier</a>
                                        <a href="gestion-articles.php?delete_id=<?php echo htmlspecialchars($article['nProduit']); ?>" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
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
    <?php include 'footer.php'; ?>
</body>
</html>