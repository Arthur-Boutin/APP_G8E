<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$idUtilisateur = $_SESSION['user']['idUtilisateur'];
$role = $_SESSION['user']['role'];
$error = '';
$success = '';
$userData = [];

try {
    if ($role === 'artisan') {
        $query = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, artisan.nom, artisan.adresse, artisan.photoProfil FROM utilisateur INNER JOIN artisan ON utilisateur.idUtilisateur = artisan.idArtisan WHERE utilisateur.idUtilisateur = :idUtilisateur";
    } else {
        $query = "SELECT utilisateur.mdp, utilisateur.statutConnexion, utilisateur.dateInscription, client.adresse, client.photoProfil FROM utilisateur INNER JOIN client ON utilisateur.idUtilisateur = client.idClient WHERE utilisateur.idUtilisateur = :idUtilisateur";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute([':idUtilisateur' => $idUtilisateur]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des informations : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }

    if ($action === 'update_info') {
        $nom = $_POST['nom'] ?? null;
        $adresse = $_POST['adresse'] ?? null;

        try {
            if ($role === 'artisan') {
                $updateQuery = "UPDATE artisan SET nom = :nom, adresse = :adresse WHERE idArtisan = :idUtilisateur";
            } else {
                $updateQuery = "UPDATE client SET adresse = :adresse WHERE idClient = :idUtilisateur";
            }

            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([':nom' => $nom, ':adresse' => $adresse, ':idUtilisateur' => $idUtilisateur]);
            $stmt = $pdo->prepare($query);
            $stmt->execute([':idUtilisateur' => $idUtilisateur]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            $success = "Vos informations ont été mises à jour avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
        }
    }

    if ($action === 'update_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d).{8,}/", $newPassword)) {
            $error = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT mdp FROM utilisateur WHERE idUtilisateur = :idUtilisateur");
                $stmt->execute([':idUtilisateur' => $idUtilisateur]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($currentPassword, $user['mdp'])) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE utilisateur SET mdp = :mdp WHERE idUtilisateur = :idUtilisateur";
                    $stmt = $pdo->prepare($updateQuery);
                    $stmt->execute([':mdp' => $hashedPassword, ':idUtilisateur' => $idUtilisateur]);
                    $success = "Votre mot de passe a été mis à jour avec succès.";
                } else {
                    $error = "Le mot de passe actuel est incorrect.";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
            }
        }
    }

    if ($action === 'update_photo' && isset($_FILES['photoProfil'])) {
        if ($_FILES['photoProfil']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['photoProfil']['tmp_name']);
            $imageType = mime_content_type($_FILES['photoProfil']['tmp_name']);

            if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/jpg'])) {
                $error = "Seuls les fichiers JPG, JPEG et PNG sont autorisés.";
            } else {
                try {
                    $updatePhotoQuery = $role === 'artisan'
                        ? "UPDATE artisan SET photoProfil = :photoProfil WHERE idArtisan = :idUtilisateur"
                        : "UPDATE client SET photoProfil = :photoProfil WHERE idClient = :idUtilisateur";

                    $stmt = $pdo->prepare($updatePhotoQuery);
                    $stmt->execute([':photoProfil' => $imageData, ':idUtilisateur' => $idUtilisateur]);

                    $stmt = $pdo->prepare($query);
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
    <style>
        .modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.6); justify-content: center; align-items: center;
        }
        .modal-content {
            background: #fff; padding: 40px; border-radius: 10px; max-width: 700px; width: 95%;
            display: flex; flex-direction: column; gap: 18px;
        }
        .modal-content label {
            font-weight: bold; display: block; margin-bottom: 5px;
        }
        .modal-content input {
            padding: 10px; border-radius: 6px; border: 1px solid #ccc; width: 100%;
        }
        .modal-content button {
            padding: 10px 20px;
        }
        .modal-content .button-group {
            display: flex; gap: 12px; justify-content: flex-end; margin-top: 15px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <h1 class="page-title">Espace Personnel</h1>
    <?php if ($error): ?><p class="error-message"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success-message"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <section class="profile-section">
        <div class="profile-block">
            <h2 class="block-title">PROFIL</h2>
            <div class="profile-photo">
                <?php if (!empty($userData['photoProfil'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($userData['photoProfil']) ?>" alt="Photo de profil">
                <?php else: ?>
                    <img src="./assets/images/LOGO.png" alt="Photo de profil par défaut">
                <?php endif; ?>
                <p class="photo-label">Photo actuelle</p>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_photo">
                    <input type="file" name="photoProfil" accept="image/*">
                    <button class="btn btn-upload" type="submit">Changer la photo</button>
                </form>
            </div>
        </div>

        <div class="info-block">
            <h2 class="block-title">INFORMATION</h2>
            <form class="info-form" method="POST">
                <input type="hidden" name="action" value="update_info">
                <label>NOM :</label>
                <input name="nom" value="<?= htmlspecialchars($userData['nom'] ?? '') ?>">
                <label>ADRESSE :</label>
                <input name="adresse" value="<?= htmlspecialchars($userData['adresse'] ?? '') ?>">
                <button class="btn btn-save" type="submit">Enregistrer</button>
            </form>
        </div>
    </section>

    <div style="text-align:center; margin-top: 40px;">
        <button onclick="document.getElementById('passwordModal').style.display='flex'" class="btn btn-save">
            Modifier le mot de passe
        </button>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="update_password">
                <label>Mot de passe actuel :</label>
                <input type="password" name="current_password" required>
                <label>Nouveau mot de passe :</label>
                <input type="password" name="new_password" required>
                <label>Confirmer le nouveau mot de passe :</label>
                <input type="password" name="confirm_password" required>
                <p style="font-size:13px;color:#555">Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.</p>
                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" onclick="document.getElementById('passwordModal').style.display='none'">Annuler</button>
                    <button class="btn btn-save" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
    <div class="logout-section" style="text-align:center; margin-top:20px;">
        <form method="POST">
            <input type="hidden" name="action" value="logout">
            <button class="btn btn-logout" type="submit">Déconnexion</button>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>
<script>
    window.onclick = function(event) {
        const modal = document.getElementById('passwordModal');
        if (event.target === modal) modal.style.display = "none";
    };
</script>
</body>
</html>