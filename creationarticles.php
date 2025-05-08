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
    <?php include 'header.php'; ?>

    <main>
        <section class="backoffice">
            <h1>Créer un nouveau produit</h1>
            <?php
            include 'session.php';

            // Vérifie si l'utilisateur est un artisan ou un administrateur
            if ($_SESSION['user']['role'] !== 'artisan' && $_SESSION['user']['role'] !== 'administrateur') {
                header('Location: index.html'); // Redirige vers la page d'accueil ou une autre page
                exit();
            }

            // Récupère l'ID de l'artisan connecté
            $idArtisan = null; // Initialise $idArtisan à null

            if ($_SESSION['user']['role'] === 'artisan') {
                if (isset($_SESSION['artisan']['idArtisan'])) {
                    $idArtisan = $_SESSION['artisan']['idArtisan'];
                } else {
                    // Si l'utilisateur est un artisan mais que les informations ne sont pas disponibles,
                    // vous pouvez rediriger l'utilisateur ou afficher un message d'erreur.
                    echo "<p class='error-message'>Les informations de l'artisan ne sont pas disponibles.</p>";
                    exit();
                }
            }

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

                // Récupérer l'ID de l'artisan
                if ($_SESSION['user']['role'] === 'administrateur') {
                    $idArtisan = intval($_POST['idArtisan']);
                } else {
                    $idArtisan = $_SESSION['artisan']['idArtisan'];
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

                    <?php if ($_SESSION['user']['role'] === 'administrateur'): ?>
                        <label for="idArtisan">Artisan</label>
                        <select id="idArtisan" name="idArtisan" required>
                            <option value="">Sélectionnez un artisan</option>
                            <?php foreach ($artisans as $artisan): ?>
                                <option value="<?php echo htmlspecialchars($artisan['IdArtisan']); ?>">
                                    <?php echo htmlspecialchars($artisan['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <label for="idArtisan">Artisan</label>
                        <input type="text" id="idArtisan" name="artisan_nom" value="<?php echo htmlspecialchars($_SESSION['artisan']['nom']); ?>" readonly>
                        <input type="hidden" name="idArtisan" value="<?php echo htmlspecialchars($idArtisan); ?>">
                    <?php endif; ?>

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

    <?php include 'footer.php'; ?>
</body>
</html>