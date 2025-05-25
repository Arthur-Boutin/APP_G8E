
<?php
$host = 'localhost';
$dbname = 'app_g8e'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur 
$password = ''; // Mot de passe 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
//test
?>
