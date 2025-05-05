<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Créer un produit</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Header intégré -->
    <header class="site-header">
  <div class="header-container">
    <!-- Logo -->
    <div class="logo">
      <a href="./index.html">NUTWORK</a>
    </div>

    <!-- Navigation -->
    <nav class="nav-menu">
      <ul>
        <li><a href="./index.html">Accueil</a></li>
        <li><a href="./articles.php">Articles</a></li>
        <li><a href="./galerie.html">Galerie</a></li>
        <li><a href="./contact.html">Contact</a></li>
      </ul>
    </nav>

    <!-- Actions -->
    <div class="header-actions">
      <form class="search-form">
        <input type="text" name="rechercher" class="search-bar" placeholder="Rechercher...">
        <button type="submit" class="search-button">🔍</button>
      </form>
      <a href="./messagerie.html" class="icon-link">
        <img src="./assets/images/Mail.png" alt="Messagerie" class="icon">
      </a>
      <a href="./panier.html" class="icon-link">
        <img src="./assets/images/truc.png" alt="Panier" class="icon">
      </a>
      <a href="./login.html" class="icon-link">
        <img src="./assets/images/Profil.png" alt="Profil" class="icon">
      </a>
    </div>
  </div>
</header>

    <main>
        <section class="backoffice">
            <h1>Créer un nouveau produit</h1>
            <?php
            include 'session.php';

            // Vérifie si l'utilisateur est un artisan
            if ($_SESSION['user']['role'] !== 'artisan') {
                header('Location: index.html'); // Redirige vers la page d'accueil ou une autre page
                exit();
            }

            // Récupère l'ID de l'artisan connecté
            $idArtisan = $_SESSION['artisan']['idArtisan'];

            // Inclure la connexion à la base de données
            include 'db_connection.php';

            // Gestion du formulaire
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = htmlspecialchars($_POST['nom']);
                $description = htmlspecialchars($_POST['description']);
                $prix = htmlspecialchars($_POST['prix']);
                $quantitee = htmlspecialchars($_POST['quantitee']);
                $idCategorie = intval($_POST['idCategorie']);
                $image = null;

                // Vérifier si une image a été téléchargée
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = file_get_contents($_FILES['image']['tmp_name']);
                }

                // Insertion dans la base de données
                $sql = "INSERT INTO produit (nom, description, prix, quantitee, idArtisan, image, idCategorie) 
                        VALUES (:nom, :description, :prix, :quantitee, :idArtisan, :image, :idCategorie)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':description' => $description,
                    ':prix' => $prix,
                    ':quantitee' => $quantitee,
                    ':idArtisan' => $idArtisan,
                    ':image' => $image,
                    ':idCategorie' => $idCategorie
                ]);

                echo "<p class='success-message'>Le produit a été ajouté avec succès !</p>";
            }

            // Récupération des artisans pour le menu déroulant
            $artisans = [];
            try {
                $stmt = $pdo->query("SELECT IdArtisan, nom FROM artisan");
                $artisans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<p class='error-message'>Erreur lors de la récupération des artisans : " . $e->getMessage() . "</p>";
            }

            // Récupération des catégories pour le menu déroulant
            $categories = [];
            try {
                $stmt = $pdo->query("SELECT idCategorie, nom FROM categorie");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<p class='error-message'>Erreur lors de la récupération des catégories : " . $e->getMessage() . "</p>";
            }
            ?>
            <div class="create-article-container">
                <div class="create-article-header">
                    <h1>Créer un Article</h1>
                </div>
                <form class="create-article-form" action="" method="POST" enctype="multipart/form-data">
                    <label for="nom">Nom de l'article</label>
                    <input type="text" id="nom" name="nom" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>

                    <label for="prix">Prix</label>
                    <input type="number" id="prix" name="prix" step="0.01" required>

                    <label for="quantitee">Quantité</label>
                    <input type="number" id="quantitee" name="quantitee" required>

                    <label for="idArtisan">Artisan</label>
                    <input type="text" id="idArtisan" name="idArtisan" value="<?php echo htmlspecialchars($_SESSION['artisan']['nom']); ?>" readonly>
                    <input type="hidden" name="idArtisan" value="<?php echo htmlspecialchars($idArtisan); ?>">

                    <label for="idCategorie">Catégorie</label>
                    <select id="idCategorie" name="idCategorie" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?php echo htmlspecialchars($categorie['idCategorie']); ?>">
                                <?php echo htmlspecialchars($categorie['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="image">Image</label>
                    <input type="file" id="image" name="image">

                    <button type="submit">Créer l'article</button>
                </form>
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