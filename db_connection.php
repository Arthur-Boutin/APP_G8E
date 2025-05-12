<!-- filepath: c:\xampp\htdocs\APPG8E\APP_G8E\db_connection.php -->
<?php
$host = 'herogu.garageisep.com';
$dbname = 'RZKUnlTU6s_craftysqui'; // Nom de votre base de données
$username = 'JG4v7FyM0M_craftysqui'; // Nom d'utilisateur par défaut pour phpMyAdmin
$password = '6F7ZwItQMi8nwmjn'; // Mot de passe par défaut pour phpMyAdmin

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
//test
?>
