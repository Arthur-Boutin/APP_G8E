<?php
session_start();

// Connexion directe à la base de données
$host = 'localhost';
$db = 'nom_de_ta_base';           // 🔁 À remplacer
$user = 'ton_utilisateur';        // 🔁 À remplacer
$pass = 'ton_mot_de_passe';       // 🔁 À remplacer

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;

try {
    if ($isAdmin) {
        // Récupère tous les utilisateurs pour l’admin
        $query = "SELECT idUtilisateur, nom, prenom, email, statut FROM utilisateur";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    } else {
        die("Accès refusé.");
    }

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
    <link rel="stylesheet" href="./P_utilisateurs.css">
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
                <th>Prénom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($utilisateurs)): ?>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['idUtilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($u['nom']); ?></td>
                        <td><?php echo htmlspecialchars($u['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['statut']); ?></td>
                        <td>
                            <a href="modifier-utilisateur.php?id=<?php echo htmlspecialchars($u['idUtilisateur']); ?>" class="btn-modify">Modifier</a>
                            <a href="supprimer-utilisateur.php?id=<?php echo htmlspecialchars($u['idUtilisateur']); ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
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
