<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Récupère le rôle de l'utilisateur
$role = $_SESSION['user']['role'];

// Si l'utilisateur est un client, redirige vers une autre page
if ($role === 'client') {
    header('Location: profil.php'); // Redirige vers la page de profil
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Espace Artisan</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Header intégré -->
    <?php include 'header.php'; ?>
    
    <main>
        <section class="backoffice-home">
            <div class="backoffice-container">
                <div class="backoffice-header">
                    <h1>Bienvenue dans votre espace artisan</h1>
                </div>
                <div class="backoffice-menu">
                    <?php if ($role === 'administrateur'): ?>
                        <!-- Liens pour l'administrateur -->
                        <a href="./gestion-articles.php">Gestion des Articles</a>
                        <a href="./creationarticles.php">Créer un Article</a>
                        <a href="./Gestion_Utilisateurs.php">Gestion des Utilisateurs</a>
                        <a href="./support.php">Support</a>
                        <a href="./gestionavis.php">Avis</a>
                        <a href="./gestion-faq.php">Gestion des FAQ</a>
                    <?php elseif ($role === 'artisan'): ?>
                        <!-- Liens pour l'artisan -->
                        <a href="./gestion-articles.php">Gestion des Articles</a>
                        <a href="./creationarticles.php">Créer un Article</a>
                        <a href="./contact.php">Support</a>
                        <a href="./gestionavis.php">Avis</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>