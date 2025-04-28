<?php
$pdo = new PDO('mysql:host=localhost;dbname=app_g8e;charset=utf8', 'root', '');


$action = $_GET['action'];

if ($action == 'list') {
    $stmt = $pdo->query("SELECT * FROM faq");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action == 'get') {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

if ($action == 'add') {
    $stmt = $pdo->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
    $stmt->execute([$_POST['question'], $_POST['answer']]);
}

if ($action == 'update') {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("UPDATE faq SET question = ?, answer = ? WHERE id = ?");
    $stmt->execute([$_POST['question'], $_POST['answer'], $id]);
}

if ($action == 'delete') {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM faq WHERE id = ?");
    $stmt->execute([$id]);
}
?>
