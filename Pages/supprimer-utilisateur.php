<?php
session_start();
include __DIR__ . '/../setup/db_connection.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    die("Accès refusé.");
}

if (!isset($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = $_GET['id'];

try {
    // Suppression dans les tables enfants d'abord
    $pdo->prepare("DELETE FROM administrateur WHERE idAdmin = :id")->execute([':id' => $id]);
    $pdo->prepare("DELETE FROM client WHERE idClient = :id")->execute([':id' => $id]);
    $pdo->prepare("DELETE FROM artisant WHERE idArtisant = :id")->execute([':id' => $id]);

    // Puis dans utilisateur
    $pdo->prepare("DELETE FROM utilisateur WHERE idUtilisateur = :id")->execute([':id' => $id]);

    // Redirection vers la bonne page
    header("Location: /APP_G8E/Pages/Gestion_Utilisateurs.php");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
