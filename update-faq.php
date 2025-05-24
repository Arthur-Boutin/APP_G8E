<?php
session_start();
include 'db_connection.php';

$id = $_POST['id'];
$action = $_POST['action'];

if ($action === 'update') {
    $answer = trim($_POST['answer']);
    $isAnswered = $answer !== '' ? 1 : 0;
    $stmt = $pdo->prepare("UPDATE faq SET answer = ?, is_answered = ? WHERE id = ?");
    $stmt->execute([$answer, $isAnswered, $id]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM faq WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: gestion-faq.php");
exit;
