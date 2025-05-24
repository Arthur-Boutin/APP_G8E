<?php
session_start();
include 'db_connection.php';

$faqs = [];
$searchTerm = trim($_GET['search'] ?? '');

if (!empty($searchTerm)) {
    $term = '%' . $searchTerm . '%';
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE question LIKE ? OR answer LIKE ?");
    $stmt->execute([$term, $term]);
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // S'il n'y a pas de résultats, on insère la question dans la base si elle n'existe pas déjà
    if (count($faqs) === 0) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM faq WHERE question = ?");
        $check->execute([$searchTerm]);
        if ($check->fetchColumn() == 0) {
            $insert = $pdo->prepare("INSERT INTO faq (question, answer, is_answered) VALUES (?, '', FALSE)");
            $insert->execute([$searchTerm]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FAQ - Nutwork</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <style>
        .main-container {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            text-align: center;
        }

        .faq-search {
            margin: 40px auto;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .faq-search input {
            padding: 10px;
            width: 60%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .faq-search button {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #6c4f3d;
            color: white;
            border: none;
            cursor: pointer;
        }

        .faq-result {
            margin-top: 30px;
            text-align: left;
            background-color: #fff3e6;
            padding: 20px;
            border-radius: 10px;
        }

        .faq-result h3 {
            color: #333;
        }

        .faq-result p {
            color: #555;
        }

        /* Modal style */
        .modal {
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff7e6;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            max-width: 400px;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-buttons button:first-child {
            background-color: #6c4f3d;
            color: white;
        }

        .modal-buttons button:last-child {
            background-color: #ccc;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-container">
    <h1>Foire Aux Questions (FAQ)</h1>
    <p>Bienvenue dans notre section FAQ dédiée aux artisans et créateurs. Voici les réponses aux questions fréquentes :</p>

    <form class="faq-search" method="get" action="">
        <input type="text" name="search" placeholder="🔍 Rechercher une question..." value="<?= htmlspecialchars($searchTerm) ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if (!empty($searchTerm)): ?>
        <?php if (count($faqs) === 0): ?>
            <div id="faq-modal" class="modal">
                <div class="modal-content">
                    <p>Nous n'avons pas encore de réponse pour cette question.<br>Voulez-vous nous contacter ?</p>
                    <div class="modal-buttons">
                        <button onclick="window.location.href='contact.php'">Oui</button>
                        <button onclick="closeModal()">Non</button>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-result">
                    <h3><?= htmlspecialchars($faq['question']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!-- Modal script -->
<script>
    function closeModal() {
        document.getElementById('faq-modal').style.display = 'none';
    }
</script>
</body>
</html>
