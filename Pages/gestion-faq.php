<?php
session_start();
include __DIR__ . '/../setup/db_connection.php';

$stmt = $pdo->query("SELECT * FROM faq ORDER BY is_answered ASC, id DESC");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion FAQ</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .faq-admin {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .faq-admin h1 {
            text-align: center;
            color: #6c4f3d;
            margin-bottom: 20px;
        }

        .faq-entry {
            width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            background-color: #fff9f2;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }




        .faq-entry:hover {
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
        }

        .faq-entry label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #333;
        }

        .faq-entry input,
        .faq-entry textarea {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .faq-entry textarea {
            resize: vertical;
        }

        .faq-entry .actions {
            margin-top: 20px;
            text-align: center;
        }
        .faq-entry .actions button {
            margin: 0 10px;
        }


        .faq-entry button {
            padding: 6px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .faq-entry button[name="action"][value="update"] {
            background-color: #6c4f3d;
            color: white;
        }

        .faq-entry button[name="action"][value="update"]:hover {
            background-color: #5a3f2e;
        }

        .faq-entry button[name="action"][value="delete"] {
            background-color: #d9534f;
            color: white;
        }

        .faq-entry button[name="action"][value="delete"]:hover {
            background-color: #c9302c;
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
