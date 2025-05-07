<?php
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Header intégré -->
<?php include 'header.php'; ?>


<main>
    <section class="gestion-utilisateurs">
        <div class="users-container">
            <div class="users-header">
                <h1>Gestion des utilisateurs</h1>
            </div>
            <table class="articles-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <!-- Exemple d'utilisateur -->
                <tr>
                    <td>1</td>
                    <td>Utilisateur 1</td>
                    <td>Artisan</td>
                    <td>
                        <button class="edit-button">Modifier</button>
                        <button class="hide-button">Cacher</button>
                        <button class="delete-button">Supprimer</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Utilisateur 2</td>
                    <td>Client</td>
                    <td>
                        <button class="edit-button">Modifier</button>
                        <button class="hide-button">Cacher</button>
                        <button class="delete-button">Supprimer</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- Footer -->
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
