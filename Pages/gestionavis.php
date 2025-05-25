<?php
// filepath: c:\xampp\htdocs\APPG8E\APP_G8E\gestionavis.php
session_start();

// Inclure la connexion à la base de données
include __DIR__ . '/../setup/db_connection.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Récupérer le rôle de l'utilisateur
$role = $_SESSION['user']['role'];

// Vérifier les permissions
if ($role === 'client') {
    echo "<p class='error-message'>Vous n'avez pas la permission d'accéder à cette page.</p>";
    exit();
}

// Gestion de la suppression d'un avis
if ($role === 'administrateur' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $idCommentaire = intval($_POST['delete_id']);

    $query = "DELETE FROM commentaire WHERE idCommentaire = :idCommentaire";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':idCommentaire' => $idCommentaire]);

    echo "<p class='success-message'>Avis supprimé avec succès !</p>";
}

// Récupérer les avis en fonction du rôle
if ($role === 'administrateur') {
    // Récupérer tous les avis
    $query = "SELECT c.idCommentaire, c.contenu, c.note, p.nom AS nom_produit, cl.nom AS nom_utilisateur
              FROM commentaire c
              JOIN produit p ON c.nProduit = p.nProduit
              JOIN client cl ON c.idClient = cl.idClient";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($role === 'artisan') {
    // Récupérer l'ID de l'artisan
    $idArtisan = $_SESSION['user']['idUtilisateur'];

    // Récupérer les avis liés aux produits de l'artisan
    $query = "SELECT c.idCommentaire, c.contenu, c.note, p.nom AS nom_produit, cl.nom AS nom_utilisateur
              FROM commentaire c
              JOIN produit p ON c.nProduit = p.nProduit
              JOIN client cl ON c.idClient = cl.idClient
              WHERE p.idArtisan = :idArtisan";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':idArtisan' => $idArtisan]);
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Avis - Backoffice</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="gestion-articles">
            <div class="articles-container">
                <div class="articles-header">
                    <h1>Gestion des Avis</h1>
                </div>
                <table class="articles-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Utilisateur</th>
                            <th>Contenu</th>
                            <th>Note</th>
                            <?php if ($role === 'administrateur'): ?>
                                <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($avis)): ?>
                            <?php foreach ($avis as $commentaire): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($commentaire['nom_produit']); ?></td>
                                    <td><?php echo htmlspecialchars($commentaire['nom_utilisateur']); ?></td>
                                    <td><?php echo htmlspecialchars($commentaire['contenu']); ?></td>
                                    <td><?php echo htmlspecialchars($commentaire['note']); ?> / 5</td>
                                    <?php if ($role === 'administrateur'): ?>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($commentaire['idCommentaire']); ?>">
                                                <button type="submit" class="delete-button">Supprimer</button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Aucun avis trouvé.</td>
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