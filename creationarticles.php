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
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <a href="./index.html">NUTWORK</a>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="./index.html">Accueil</a></li>
                    <li><a href="./articles.html">Articles</a></li>
                    <li><a href="./galerie.html">Galerie</a></li>
                    <li><a href="./contact.html">Contact</a></li>
                </ul>
            </nav>
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

                // Insertion dans la base de données
                $sql = "INSERT INTO produit (nom, description, prix, quantitee, idArtisan) VALUES (:nom, :description, :prix, :quantitee, :idArtisan)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':description' => $description,
                    ':prix' => $prix,
                    ':quantitee' => $quantitee,
                    ':idArtisan' => $idArtisan
                ]);

                echo "<p class='success-message'>Le produit a été ajouté avec succès !</p>";
            }

            // Récupération des artisans pour le menu déroulant
            $artisans = [];
            try {
                $stmt = $pdo->query("SELECT id, nom FROM artisan"); // Assurez-vous que la table 'artisan' existe
                $artisans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<p class='error-message'>Erreur lors de la récupération des artisans : " . $e->getMessage() . "</p>";
            }
            ?>
            <form action="" method="POST" class="article-form">
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
                            <option value="<?= htmlspecialchars($artisan['id']) ?>">
                                <?= htmlspecialchars($artisan['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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