<?php
ob_start();
// Vérifie si une session est déjà active avant de démarrer une nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <div class="logo">
            <a href="./index.php">CraftySquirrel</a>
        </div>

        <!-- Navigation -->
        <nav class="nav-menu">
            <ul>
                <li><a href="./index.php">Accueil</a></li>
                <li><a href="./articles.php">Articles</a></li>
                <?php if (isset($_SESSION['user']['role']) && ($_SESSION['user']['role'] === 'artisan' || $_SESSION['user']['role'] === 'administrateur')): ?>
                    <li><a href="./backoffice-home.php">Espace Artisans</a></li>
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
                <img src="../assets/images/Mail.png" alt="Messagerie" class="icon">
            </a>
            <a href="./panier.php" class="icon-link">
                <img src="../assets/images/truc.png" alt="Panier" class="icon">
            </a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="./profil.php" class="icon-link" style="display: flex; flex-direction: column; align-items: center;">
                    <?php
                    // Affichage de la photo de profil si elle existe
                    if (!empty($_SESSION['user']['photoProfil'])) {
                        // Si la photo est stockée en BLOB (binaire) dans la BDD
                        if (base64_encode(base64_decode($_SESSION['user']['photoProfil'], true)) === $_SESSION['user']['photoProfil']) {
                            // Déjà en base64
                            $src = 'data:image/jpeg;base64,' . $_SESSION['user']['photoProfil'];
                        } else {
                            // Chemin vers le fichier image
                            $src = htmlspecialchars($_SESSION['user']['photoProfil']);
                        }
                        echo '<img src="' . $src . '" alt="Profil" class="icon" style="border-radius:50%;width:40px;height:40px;object-fit:cover;">';
                    } else {
                        // Image par défaut
                        echo '<img src="../assets/images/Profil.png" alt="Profil" class="icon">';
                    }
                    ?>
                    <?php if (!empty($_SESSION['user']['prenom'])): ?>
                        <span style="font-size:12px;color:#333;margin-top:2px;"><?php echo htmlspecialchars($_SESSION['user']['prenom']); ?></span>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <a href="./login.php" class="icon-link">
                    <img src="../assets/images/Profil.png" alt="Profil" class="icon">
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php
ob_end_flush();
?>
