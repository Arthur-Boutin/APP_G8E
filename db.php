<?php
$host = 'herogu.garageisep.com';
$user = 'JG4v7FyM0M_craftysqui';
$password = '6F7ZwItQMi8nwmjn'; // À adapter selon votre config
$database = 'RZKUnlTU6s_craftysqui';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}
?>
