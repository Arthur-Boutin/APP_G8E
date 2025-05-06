<?php
session_start();
include 'db_connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: login.html');
    exit();
}

// Récupère les informations de l'utilisateur connecté
$idUtilisateur = $_SESSION['idUtilisateur'];
$query = "SELECT * FROM utilisateur WHERE idUtilisateur = :idUtilisateur";
$stmt = $pdo->prepare($query);
$stmt->execute([':idUtilisateur' => $idUtilisateur]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas, déconnecte-le
if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Stocke toutes les informations utilisateur dans la session
$_SESSION['user'] = $user;

// Si l'utilisateur est un artisan, récupère ses informations spécifiques
if ($user['role'] === 'artisan') {
    $queryArtisan = "SELECT * FROM artisan WHERE idArtisan = :idArtisan";
    $stmtArtisan = $pdo->prepare($queryArtisan);
    $stmtArtisan->execute([':idArtisan' => $idUtilisateur]);
    $artisan = $stmtArtisan->fetch(PDO::FETCH_ASSOC);

    // Si l'artisan existe, ajoute ses informations à la session
    if ($artisan) {
        $_SESSION['artisan'] = $artisan;
    }
}

// Si l'utilisateur est un client, récupère ses informations spécifiques
if ($user['role'] === 'client') {
    $queryClient = "SELECT * FROM client WHERE idClient = :idClient";
    $stmtClient = $pdo->prepare($queryClient);
    $stmtClient->execute([':idClient' => $idUtilisateur]);
    $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

    // Si le client existe, ajoute ses informations à la session
    if ($client) {
        $_SESSION['client'] = $client;
    }
}
?>