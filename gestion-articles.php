<?php
// Inclure uniquement la connexion à la base de données
include 'db_connection.php';

// Vérifier si une action de suppression a été demandée
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    try {
        $delete_query = "DELETE FROM produit WHERE nProduit = :id"; // Mise à jour pour la table produit
        $stmt = $pdo->prepare($delete_query); // Utilisation de PDO
        $stmt->execute([':id' => $delete_id]);
        header("Location: gestion-articles.php"); // Rediriger après suppression
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutwork - Gestion des produits</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <!-- Header intégré -->
    <header class="site-header">
        <div class="header-container">
            <div la="logo">
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
        <section class="gestion-articles">
            <h1>Gestion des produits</h1>
            <table class="articles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Récupérer les produits depuis la base de données
                        $query = "SELECT nProduit, nom, description FROM produit"; // Mise à jour pour la table produit
                        $stmt = $pdo->query($query); // Utilisation de PDO
                        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($produits) > 0) {
                            foreach ($produits as $produit) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($produit['nProduit']) . "</td>
                                        <td>" . htmlspecialchars($produit['nom']) . "</td>
                                        <td>" . htmlspecialchars($produit['description']) . "</td>
                                        <td>
                                            <a href='modifier-article.php?id=" . htmlspecialchars($produit['nProduit']) . "' class='btn-modify'>Modifier</a>
                                            <a href='gestion-articles.php?delete_id=" . htmlspecialchars($produit['nProduit']) . "' class='btn-delete' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce produit ?\")'>Supprimer</a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Aucun produit trouvé.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='4'>Erreur lors de la récupération des produits : " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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