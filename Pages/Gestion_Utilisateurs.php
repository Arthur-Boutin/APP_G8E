<?php
session_start();

// Connexion à la base de données
include __DIR__ . '/../setup/db_connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Vérifie si l'utilisateur est un administrateur
if ($_SESSION['user']['role'] !== 'administrateur') {
    die("Accès refusé.");
}

try {
    // On récupère tous les utilisateurs avec leur rôle
    $query = "
        SELECT 
            u.idUtilisateur, 
            u.role, 
            u.statutConnexion,
            -- Pour les clients
            c.nom AS nom_client, c.email AS email_client, c.adresse AS adresse_client, c.photoProfil AS photo_client,
            -- Pour les artisans
            a.nom AS nom_artisan, a.email AS email_artisan, a.adresse AS adresse_artisan, a.photoProfil AS photo_artisan
        FROM utilisateur u
        LEFT JOIN client c ON u.idUtilisateur = c.idClient
        LEFT JOIN artisan a ON u.idUtilisateur = a.idArtisan
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nutwork - Gestion des utilisateurs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="P_utilisateurs.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <section class="gestion-utilisateurs">
        <h1>Gestion des utilisateurs</h1>
        <table class="utilisateurs-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut de Connexion</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($utilisateurs)): ?>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['idUtilisateur']); ?></td>
                        <td>
                            <?php
                            if ($u['role'] === 'client') {
                                echo htmlspecialchars($u['nom_client']);
                            } elseif ($u['role'] === 'artisan') {
                                echo htmlspecialchars($u['nom_artisan']);
                            } else {
                                echo 'Administrateur';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($u['role'] === 'client') {
                                echo htmlspecialchars($u['email_client']);
                            } elseif ($u['role'] === 'artisan') {
                                echo htmlspecialchars($u['email_artisan']);
                            } else {
                                echo '';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td><?php echo htmlspecialchars($u['statutConnexion']); ?></td>
                        <td>
                            <a href="modifier-utilisateur.php?id=<?php echo urlencode($u['idUtilisateur']); ?>" class="btn-modify">Modifier</a>
                            <a href="Gestion_Utilisateurs.php?id=<?php echo urlencode($u['idUtilisateur']); ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Aucun utilisateur trouvé.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
