<?php
session_start();
require 'db_connection.php';

// Vérifie si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header('Location: login.php');
    exit();
}

// Vérifie si un ID est fourni
if (!isset($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = intval($_GET['id']);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Préparation de la requête SQL
    if (!empty($mdp)) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateur SET email = :email, mdp = :mdp WHERE idUtilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':mdp' => $mdp_hash, ':id' => $id]);
    } else {
        $sql = "UPDATE utilisateur SET email = :email WHERE idUtilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':id' => $id]);
    }

    header('Location: Gestion_Utilisateurs.php');
    exit();
}

// Récupération des infos de l'utilisateur
$stmt = $pdo->prepare("SELECT email FROM utilisateur WHERE idUtilisateur = :id");
$stmt->execute([':id' => $id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
</head>
<body>
    <h1>Modifier l'utilisateur</h1>
    <form method="post">
        <label>Email :</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required><br>

        <label>Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
        <input type="password" name="mdp"><br>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
