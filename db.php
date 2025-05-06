<?php
$host = 'localhost';
$user = 'root';
$password = ''; // À adapter selon votre config
$database = 'app_g8e';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}
?>
