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
            <p>Aucun résultat trouvé pour "<?= htmlspecialchars($searchTerm) ?>"</p>
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
</body>
</html>
