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
          <li><a href="./articles.html">Articles</a></li>
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
            // Connexion à la base de données
            $host = 'localhost';
            $dbname = 'app_g8e'; // Nom de votre base de données
            $username = 'root'; // Nom d'utilisateur par défaut pour phpMyAdmin
            $password = ''; // Mot de passe par défaut pour phpMyAdmin

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }

            // Gestion du formulaire
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = htmlspecialchars($_POST['nom']);
                $description = htmlspecialchars($_POST['description']);
                $prix = htmlspecialchars($_POST['prix']);
                $quantitee = htmlspecialchars($_POST['quantitee']);
                $idArtisan = htmlspecialchars($_POST['idArtisan']);
                $image = null;

                // Vérifier si une image a été téléchargée
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = file_get_contents($_FILES['image']['tmp_name']);
                }

                // Insertion dans la base de données
                $sql = "INSERT INTO produit (nom, description, prix, quantitee, idArtisan, image) 
                        VALUES (:nom, :description, :prix, :quantitee, :idArtisan, :image)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':description' => $description,
                    ':prix' => $prix,
                    ':quantitee' => $quantitee,
                    ':idArtisan' => $idArtisan,
                    ':image' => $image
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
            ?>
            <form action="" method="POST" enctype="multipart/form-data" class="article-form">
                <div class="form-group">
                    <label for="nom">Nom du produit :</label>
                    <input type="text" id="nom" name="nom" placeholder="Entrez le nom" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" rows="5" placeholder="Entrez la description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="prix">Prix :</label>
                    <input type="number" id="prix" name="prix" step="0.01" placeholder="Entrez le prix" required>
                </div>
                <div class="form-group">
                    <label for="quantitee">Quantité :</label>
                    <input type="number" id="quantitee" name="quantitee" placeholder="Entrez la quantité" required>
                </div>
                <div class="form-group">
                    <label for="idArtisan">Artisan :</label>
                    <select id="idArtisan" name="idArtisan" required>
                        <option value="" disabled selected>Choisissez un artisan</option>
                        <?php foreach ($artisans as $artisan): ?>
                            <option value="<?php echo htmlspecialchars($artisan['IdArtisan']); ?>">
                                <?php echo htmlspecialchars($artisan['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Image du produit :</label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg" required>
                </div>
                <button type="submit" class="submit-button">Créer le produit</button>
            </form>
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