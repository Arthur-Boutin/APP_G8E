<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\FicheProduit.php -->
<?php
session_start();

// Inclure la connexion à la base de données
include __DIR__ . '/../setup/db_connection.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les données du produit depuis la base de données
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du produit manquant.");
}

$id = intval($_GET['id']);

$query = "SELECT nom, description, prix, quantitee, image, tempsFabrication, tailles, materiaux, couleur FROM produit WHERE nProduit = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}

// Gestion de l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantite']) && isset($_POST['couleur']) && isset($_POST['taille']) && isset($_POST['materiau'])) {
    $quantite = intval($_POST['quantite']);
    $couleur = htmlspecialchars($_POST['couleur']);
    $taille = htmlspecialchars($_POST['taille']);
    $materiau = htmlspecialchars($_POST['materiau']);

    if ($quantite > 0) {
        // Vérifier si le produit est déjà dans le panier
        $query = "SELECT quantite FROM panierachat WHERE idProduit = :idProduit AND couleur = :couleur AND taille = :taille AND materiau = :materiau";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':idProduit' => $id, ':couleur' => $couleur, ':taille' => $taille, ':materiau' => $materiau]);
        $panierItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($panierItem) {
            // Si le produit est déjà dans le panier, mettre à jour la quantité
            $nouvelleQuantite = $panierItem['quantite'] + $quantite;
            $query = "UPDATE panierachat SET quantite = :quantite, dateAjoutee = :dateAjoutee WHERE idProduit = :idProduit AND couleur = :couleur AND taille = :taille AND materiau = :materiau";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':quantite' => $nouvelleQuantite,
                ':dateAjoutee' => time(),
                ':idProduit' => $id,
                ':couleur' => $couleur,
                ':taille' => $taille,
                ':materiau' => $materiau
            ]);
        } else {
            // Si le produit n'est pas dans le panier, l'ajouter
            $query = "INSERT INTO panierachat (idProduit, quantite, dateAjoutee, couleur, taille, materiau) VALUES (:idProduit, :quantite, :dateAjoutee, :couleur, :taille, :materiau)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':idProduit' => $id,
                ':quantite' => $quantite,
                ':dateAjoutee' => time(),
                ':couleur' => $couleur,
                ':taille' => $taille,
                ':materiau' => $materiau
            ]);
        }

        echo "<p class='success-message'>Produit ajouté au panier avec succès !</p>";
    } else {
        echo "<p class='error-message'>Veuillez sélectionner une quantité valide.</p>";
    }
}

// Gestion de l'ajout d'un avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu']) && isset($_POST['note'])) {
    $contenu = htmlspecialchars($_POST['contenu']);
    $note = floatval($_POST['note']);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        echo "<p class='error-message'>Vous devez être connecté pour laisser un avis.</p>";
    } else {
        $idClient = $_SESSION['user']['idUtilisateur'];

        // Insérer le commentaire dans la base de données
        $query = "INSERT INTO commentaire (idClient, nProduit, contenu, note) VALUES (:idClient, :nProduit, :contenu, :note)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':idClient' => $idClient,
            ':nProduit' => $id,
            ':contenu' => $contenu,
            ':note' => $note
        ]);

        echo "<p class='success-message'>Votre avis a été ajouté avec succès !</p>";
    }
}

// Récupérer les avis existants
$query = "SELECT c.contenu, c.note, cl.nom AS nom_utilisateur
          FROM commentaire c
          JOIN client cl ON c.idClient = cl.idClient
          WHERE c.nProduit = :nProduit";
$stmt = $pdo->prepare($query);
$stmt->execute([':nProduit' => $id]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produit['nom']); ?> - Nutwork</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <div class="product-container">
        <div class="product-top">
            <div class="product-image">
                <?php if (!empty($produit['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($produit['image']); ?>"
                         alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                <?php else: ?>
                    <img src="./assets/images/default.jpg" alt="Image par défaut">
                <?php endif; ?>
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
                <div class="product-price"><?php echo htmlspecialchars($produit['prix']); ?> €</div>
                <p class="product-description"><?php echo htmlspecialchars($produit['description']); ?></p>
                <p class="product-fabrication-time">Délai de fabrication : <?php echo htmlspecialchars($produit['tempsFabrication']); ?> jours</p>

                <form action="" method="POST" class="add-to-cart-form">
                    <label for="quantite">Quantité :</label>
                    <input type="number" id="quantite" name="quantite" min="1"
                           max="<?php echo htmlspecialchars($produit['quantitee']); ?>" value="1" required>

                    <label for="couleur">Couleur :</label>
                    <?php
                    $couleurs = explode(',', $produit['couleur']);
                    if (!empty($couleurs)): ?>
                        <select id="couleur" name="couleur" required class="custom-select">
                            <?php foreach ($couleurs as $couleur): ?>
                                <option value="<?php echo htmlspecialchars($couleur); ?>"><?php echo htmlspecialchars($couleur); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p>Aucune couleur disponible</p>
                    <?php endif; ?>

                    <label for="taille">Taille :</label>
                    <?php
                    $tailles = explode(',', $produit['tailles']);
                    if (!empty($tailles)): ?>
                        <select id="taille" name="taille" required class="custom-select">
                            <?php foreach ($tailles as $taille): ?>
                                <option value="<?php echo htmlspecialchars($taille); ?>"><?php echo htmlspecialchars($taille); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p>Aucune taille disponible</p>
                    <?php endif; ?>

                    <label for="materiau">Matériau :</label>
                    <?php
                    $materiaux = explode(',', $produit['materiaux']);
                    if (!empty($materiaux)): ?>
                        <select id="materiau" name="materiau" required class="custom-select">
                            <?php foreach ($materiaux as $materiau): ?>
                                <option value="<?php echo htmlspecialchars($materiau); ?>"><?php echo htmlspecialchars($materiau); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p>Aucun matériau disponible</p>
                    <?php endif; ?>

                    <button type="submit" class="add-to-cart-button">Ajouter au panier</button>
                </form>
            </div>
        </div>

        <!-- Afficher les avis existants -->
        <div class="existing-reviews-container">
            <h3>Avis des utilisateurs</h3>
            <?php if (empty($avis)): ?>
                <p>Aucun avis pour le moment.</p>
            <?php else: ?>
                <?php foreach ($avis as $commentaire): ?>
                    <div class="review">
                        <p><strong>Utilisateur :</strong> <?php echo htmlspecialchars($commentaire['nom_utilisateur']); ?></p>
                        <p><strong>Note :</strong> <?php echo htmlspecialchars($commentaire['note']); ?> / 5</p>
                        <p><?php echo htmlspecialchars($commentaire['contenu']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Formulaire pour ajouter un avis -->
        <div class="add-review-container">
            <h3>Ajouter un avis</h3>
            <form action="" method="POST" class="add-review-form">
                <label for="contenu">Commentaire :</label>
                <textarea id="contenu" name="contenu" required></textarea>

                <label for="note">Note (sur 5) :</label>
                <input type="number" id="note" name="note" min="1" max="5" required>

                <button type="submit" class="add-review-button">Envoyer</button>
            </form>
        </div>


    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
