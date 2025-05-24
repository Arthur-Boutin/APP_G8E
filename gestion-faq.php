<?php
session_start();
include 'db_connection.php';

$stmt = $pdo->query("SELECT * FROM faq ORDER BY is_answered ASC, id DESC");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion FAQ</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .faq-admin {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }
        .faq-entry {
            border: 1px solid #ccc;
            margin: 10px 0;
            padding: 15px;
            background-color: #fff9f2;
        }
        .faq-entry input, .faq-entry textarea {
            width: 100%;
            margin-top: 5px;
        }
        .faq-entry .actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="faq-admin">
    <h1>Gestion des FAQ</h1>
    <?php foreach ($faqs as $faq): ?>
        <form class="faq-entry" method="post" action="update-faq.php">
            <input type="hidden" name="id" value="<?= $faq['id'] ?>">
            <label>Question :</label>
            <input type="text" name="question" value="<?= htmlspecialchars($faq['question']) ?>" readonly>
            <label>Réponse :</label>
            <textarea name="answer" rows="4"><?= htmlspecialchars($faq['answer']) ?></textarea>
            <div class="actions">
                <button type="submit" name="action" value="update">Modifier</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Supprimer cette FAQ ?')">Supprimer</button>
            </div>
        </form>
    <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
