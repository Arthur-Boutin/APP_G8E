<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$idUtilisateur = $_SESSION['user']['idUtilisateur'];
$role = $_SESSION['user']['role'];
$error = '';
$success = '';
$userData = [];

// Récupérer les informations de l'utilisateur connecté
try {
    if ($role === 'artisan') {
        $query = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, 
                         artisan.nom, artisan.adresse, artisan.photoProfil 
                  FROM utilisateur 
                  INNER JOIN artisan ON utilisateur.idUtilisateur = artisan.idArtisan 
                  WHERE utilisateur.idUtilisateur = :idUtilisateur";
    } else {
        $query = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, 
                         client.adresse, client.photoProfil 
                  FROM utilisateur 
                  INNER JOIN client ON utilisateur.idUtilisateur = client.idClient 
                  WHERE utilisateur.idUtilisateur = :idUtilisateur";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute([':idUtilisateur' => $idUtilisateur]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des informations : " . $e->getMessage();
}

// Vérifier quelle action est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Déconnexion
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }

    // Mise à jour des informations utilisateur
    if ($action === 'update_info') {
        $nom = $_POST['nom'] ?? null;
        $adresse = $_POST['adresse'] ?? null;

        try {
            if ($role === 'artisan') {
                $updateQuery = "UPDATE artisan 
                                SET nom = :nom, adresse = :adresse 
                                WHERE idArtisan = :idUtilisateur";
            } else {
                $updateQuery = "UPDATE client 
                                SET adresse = :adresse 
                                WHERE idClient = :idUtilisateur";
            }

            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([
                ':nom' => $nom,
                ':adresse' => $adresse,
                ':idUtilisateur' => $idUtilisateur
            ]);

            // Recharge les données de l'utilisateur après la mise à jour
            $stmt = $pdo->prepare($query);
            $stmt->execute([':idUtilisateur' => $idUtilisateur]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            $success = "Vos informations ont été mises à jour avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
        }
    }

    // Mise à jour du mot de passe
    if ($action === 'update_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } else {
            try {
                // Vérifier le mot de passe actuel
                $query = "SELECT mdp FROM utilisateur WHERE idUtilisateur = :idUtilisateur";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':idUtilisateur' => $idUtilisateur]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($currentPassword, $user['mdp'])) {
                    // Mettre à jour le mot de passe
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE utilisateur SET mdp = :mdp WHERE idUtilisateur = :idUtilisateur";
                    $stmt = $pdo->prepare($updateQuery);
                    $stmt->execute([
                        ':mdp' => $hashedPassword,
                        ':idUtilisateur' => $idUtilisateur
                    ]);

                    $success = "Votre mot de passe a été mis à jour avec succès.";
                } else {
                    $error = "Le mot de passe actuel est incorrect.";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
            }
        }
    }

    // Mise à jour de la photo de profil
    if ($action === 'update_photo' && isset($_FILES['photoProfil'])) {
        if ($_FILES['photoProfil']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['photoProfil']['tmp_name']); // Lire le contenu binaire de l'image
            $imageType = mime_content_type($_FILES['photoProfil']['tmp_name']); // Obtenir le type MIME de l'image

            // Vérifier si le fichier est une image valide
            if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/jpg'])) {
                $error = "Seuls les fichiers JPG, JPEG et PNG sont autorisés.";
            } else {
                try {
                    // Requête pour mettre à jour la photo de profil
                    if ($role === 'artisan') {
                        $updatePhotoQuery = "UPDATE artisan SET photoProfil = :photoProfil WHERE idArtisan = :idUtilisateur";
                    } else {
                        $updatePhotoQuery = "UPDATE client SET photoProfil = :photoProfil WHERE idClient = :idUtilisateur";
                    }

                    $stmt = $pdo->prepare($updatePhotoQuery);
                    $stmt->execute([
                        ':photoProfil' => $imageData,
                        ':idUtilisateur' => $idUtilisateur
                    ]);

                    // Requête pour recharger les données de l'utilisateur
                    if ($role === 'artisan') {
                        $reloadQuery = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, 
                                           artisan.nom, artisan.adresse, artisan.photoProfil 
                                    FROM utilisateur 
                                    INNER JOIN artisan ON utilisateur.idUtilisateur = artisan.idArtisan 
                                    WHERE utilisateur.idUtilisateur = :idUtilisateur";
                    } else {
                        $reloadQuery = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, 
                                           client.adresse, client.photoProfil 
                                    FROM utilisateur 
                                    INNER JOIN client ON utilisateur.idUtilisateur = client.idClient 
                                    WHERE utilisateur.idUtilisateur = :idUtilisateur";
                    }

                    $stmt = $pdo->prepare($reloadQuery);
                    $stmt->execute([':idUtilisateur' => $idUtilisateur]);
                    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                    $success = "Votre photo de profil a été mise à jour avec succès.";
                } catch (PDOException $e) {
                    $error = "Erreur lors de la mise à jour de la photo de profil : " . $e->getMessage();
                }
            }
        } else {
            $error = "Erreur lors du téléchargement de la photo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil – Nutwork</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<!-- ===========================  HEADER  ================================== -->
<?php include 'header.php'; ?>

<!-- ===========================  MAIN  ==================================== -->
<main>
  <h1 class="page-title">Espace Personnel</h1>

  <!-- Affichage des messages d'erreur ou de succès -->
  <?php if (!empty($error)): ?>
    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
  <?php elseif (!empty($success)): ?>
    <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
  <?php endif; ?>

  <!-- PROFIL + INFORMATION -------------------------------------------------->
  <section class="profile-section">
    <div class="profile-block">
      <h2 class="block-title">PROFIL</h2>
      <div class="profile-photo">
        <?php if (!empty($userData['photoProfil'])): ?>
            <!-- Affiche l'image de profil depuis la base de données -->
            <img src="data:image/jpeg;base64,<?php echo base64_encode($userData['photoProfil']); ?>" alt="Photo de profil">
        <?php else: ?>
            <!-- Affiche une image par défaut si aucune photo de profil n'est disponible -->
            <img src="./assets/images/LOGO.png" alt="Photo de profil par défaut">
        <?php endif; ?>
        <p class="photo-label">Photo actuelle</p>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_photo">
            <input type="file" name="photoProfil" accept="image/*">
            <button class="btn btn-upload" type="submit">Changer la photo</button>
        </form>
      </div>
    </div>

    <div class="info-block">
      <h2 class="block-title">INFORMATION</h2>
      <form class="info-form" action="" method="POST">
        <input type="hidden" name="action" value="update_info">
        <label>NOM :</label>
        <input name="nom" value="<?php echo htmlspecialchars($userData['nom'] ?? ''); ?>" placeholder="Entrez votre nom">
        <label>ADRESSE :</label>
        <input name="adresse" value="<?php echo htmlspecialchars($userData['adresse'] ?? ''); ?>" placeholder="Entrez votre adresse">
        <button class="btn btn-save" type="submit">Enregistrer</button>
      </form>
    </div>
  </section>

  <!-- MODIFIER MOT DE PASSE -------------------------------------------------->
  <section class="password-section">
    <h2 class="block-title">Modifier le mot de passe</h2>
    <form class="password-form" action="" method="POST">
      <input type="hidden" name="action" value="update_password">
      <label>Mot de passe actuel :</label>
      <input type="password" name="current_password" required>
      <label>Nouveau mot de passe :</label>
      <input type="password" name="new_password" required>
      <label>Confirmer le nouveau mot de passe :</label>
      <input type="password" name="confirm_password" required>
      <button class="btn btn-save" type="submit">Mettre à jour</button>
    </form>
  </section>

  <!-- Bouton de déconnexion -->
  <div class="logout-section">
    <form action="" method="POST">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-logout" type="submit">Déconnexion</button>
    </form>
  </div>
</main>

<!-- ===========================  FOOTER  ================================== -->
<?php include 'footer.php'; ?>
<script src="./script.js"></script>
</body>
</html>