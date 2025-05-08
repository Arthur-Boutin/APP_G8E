<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\header.php -->
<?php
// Vérifie si une session est déjà active avant de démarrer une nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <div class="logo">
            <a href="./index.php">CraftySquirel</a>
        </div>

        <!-- Navigation -->
        <nav class="nav-menu">
            <ul>
                <li><a href="./index.php">Accueil</a></li>
                <li><a href="./articles.php">Articles</a></li>
                <?php if (isset($_SESSION['user']['role']) && ($_SESSION['user']['role'] === 'artisan' || $_SESSION['user']['role'] === 'administrateur')): ?>
                    <li><a href="./backoffice-home.php">Backoffice</a></li>
                <?php else: ?>
                    <li><a href="#">Galerie</a></li>
                <?php endif; ?>
                <li><a href="./contact.php">Contact</a></li>
            </ul>
        </nav>

        <!-- Actions -->
        <div class="header-actions">
            <form class="search-form">
                <input type="text" name="rechercher" class="search-bar" placeholder="Rechercher...">
                <button type="submit" class="search-button">🔍</button>
            </form>
            <a href="./messagerie.php" class="icon-link">
                <img src="./assets/images/Mail.png" alt="Messagerie" class="icon">
            </a>
            <a href="./panier.php" class="icon-link">
                <img src="./assets/images/truc.png" alt="Panier" class="icon">
            </a>
            <a href="<?php echo isset($_SESSION['user']) ? './profil.php' : './login.php'; ?>" class="icon-link">
                <img src="./assets/images/Profil.png" alt="Profil" class="icon">
            </a>
        </div>
    </div>
</header>
