<?php
// support.php
include __DIR__ . '/../setup/db_connection.php';
include 'header.php';

// Marquer comme traité
if (isset($_GET['traite'])) {
    $stmt = $pdo->prepare("UPDATE support_ticket SET traite=1 WHERE id=?");
    $stmt->execute([$_GET['traite']]);
}

// Envoi de la réponse par mail
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'repondre') {
    $to = $_POST['email'];
    $prenom = $_POST['prenom'];
    $sujet = "Réponse à votre ticket : " . $_POST['sujet'];
    $message = "Bonjour $prenom,\n\nVous avez envoyé le message suivant :\n" . $_POST['message'] . "\n\nNotre réponse :\n" . $_POST['reponse'];
    $headers = "From: support@votre-site.com\r\nReply-To: support@votre-site.com";
    mail($to, $sujet, $message, $headers);
    echo "<div class='success-message' style='color:green;text-align:center;'>Réponse envoyée à $to</div>";
}

// Récupérer tous les tickets
$tickets = $pdo->query("SELECT * FROM support_ticket ORDER BY date_envoi DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Support - Tickets</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .support-container {
            max-width: 900px;
            margin: 40px auto 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            padding: 32px 24px;
        }
        .support-title {
            text-align: center;
            color: #a87940;
            font-size: 2.2rem;
            margin-bottom: 30px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .tickets {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        .ticket {
            border: 1.5px solid #a87940;
            border-radius: 10px;
            padding: 22px 18px 18px 18px;
            background: #f9f7f4;
            box-shadow: 0 2px 8px rgba(168,121,64,0.07);
            position: relative;
        }
        .ticket.traite {
            background: #e0ffe0;
            border-color: #5cb85c;
        }
        .ticket strong {
            font-size: 1.1rem;
            color: #333;
        }
        .ticket em {
            color: #a87940;
            font-weight: 600;
            font-style: normal;
            display: block;
            margin: 6px 0 2px 0;
        }
        .ticket small {
            color: #888;
            font-size: 0.95em;
        }
        .ticket p {
            margin: 10px 0 14px 0;
            color: #444;
        }
        .ticket form {
            margin-bottom: 10px;
        }
        .ticket textarea {
            width: 100%;
            min-height: 60px;
            border-radius: 6px;
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 8px;
            font-size: 1em;
            resize: vertical;
        }
        .ticket button {
            background: #a87940;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .ticket button:hover {
            background: #7a552d;
        }
        .ticket .ticket-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .ticket .ticket-status {
            font-weight: bold;
            color: #5cb85c;
        }
        .ticket .ticket-link {
            color: #a87940;
            text-decoration: underline;
            cursor: pointer;
            font-size: 0.98em;
        }
    </style>
</head>
<body>
<main>
    <div class="support-container">
        <div class="support-title">Tickets de support</div>
        <div class="tickets">
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket<?= $ticket['traite'] ? ' traite' : '' ?>">
                    <strong><?= htmlspecialchars($ticket['prenom'] . ' ' . $ticket['nom']) ?></strong>
                    <span style="color:#888; font-size:0.98em;">(<?= htmlspecialchars($ticket['email']) ?>)</span>
                    <em><?= htmlspecialchars($ticket['sujet']) ?></em>
                    <small><?= $ticket['date_envoi'] ?></small>
                    <p><?= nl2br(htmlspecialchars($ticket['message'])) ?></p>
                    <form method="post" action="support.php?action=repondre">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($ticket['email']) ?>">
                        <input type="hidden" name="prenom" value="<?= htmlspecialchars($ticket['prenom']) ?>">
                        <input type="hidden" name="sujet" value="<?= htmlspecialchars($ticket['sujet']) ?>">
                        <input type="hidden" name="message" value="<?= htmlspecialchars($ticket['message']) ?>">
                        <textarea name="reponse" placeholder="Votre réponse"></textarea>
                        <button type="submit">Répondre par mail</button>
                    </form>
                    <div class="ticket-actions">
                        <?php if (!$ticket['traite']): ?>
                            <a href="support.php?traite=<?= $ticket['id'] ?>" class="ticket-link">Marquer comme traité</a>
                        <?php else: ?>
                            <span class="ticket-status">Traité</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($tickets)): ?>
                <div style="text-align:center;color:#888;">Aucun ticket pour le moment.</div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>