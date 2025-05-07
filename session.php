<?php
// Vérifie si une session est déjà active avant de démarrer une nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: login.php');
    exit();
}

// Récupère les informations de l'utilisateur connecté
$idUtilisateur = $_SESSION['idUtilisateur'];
$query = "SELECT * FROM utilisateur WHERE idUtilisateur = :idUtilisateur";
$stmt = $pdo->prepare($query);
$stmt->execute([':idUtilisateur' => $idUtilisateur]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Stocke les informations utilisateur dans la session
$_SESSION['user'] = $user;

// Si l'utilisateur est un artisan, récupère ses informations spécifiques
if ($user['role'] === 'artisan') {
    $queryArtisan = "SELECT * FROM artisan WHERE idArtisan = :idArtisan";
    $stmtArtisan = $pdo->prepare($queryArtisan);
    $stmtArtisan->execute([':idArtisan' => $idUtilisateur]);
    $artisan = $stmtArtisan->fetch(PDO::FETCH_ASSOC);

    if ($artisan) {
        $_SESSION['artisan'] = $artisan;
    }
}

// Si l'utilisateur est un administrateur, ajoute une clé spécifique dans la session
if ($user['role'] === 'administrateur') {
    $_SESSION['isAdmin'] = true;
}
?>