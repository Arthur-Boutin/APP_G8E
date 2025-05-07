<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Backoffice</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Header intégré -->
    <?php include 'header.php'; ?>
    
    <main>
        <section class="backoffice-home">
            <div class="backoffice-container">
                <div class="backoffice-header">
                    <h1>Bienvenue dans le Backoffice</h1>
                </div>
                <div class="backoffice-menu">
                    <a href="./gestion-articles.php">Gestion des Articles</a>
                    <a href="./creationarticles.php">Créer un Article</a>
                    <a href="./Gestion_Utilisateurs.php">Gestion des Utilisateurs</a>
                    <a href="./statistiques.php">Statistiques</a>
                    <a href="./parametres.php">Paramètres</a>
                    <a href="./support.php">Support</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer intégré -->
    <footer class="site-footer">
        <div>
            <h4>À propos de Nutwork</h4>
            <p><a href="./contact.html">Contactez-nous</a></p>
            <p>À propos de nous</p>
            <p>Blog</p>
            <p><a href="./faq.html">FAQ</a></p>
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