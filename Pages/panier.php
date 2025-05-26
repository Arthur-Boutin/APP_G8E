<?php
session_start();
// Connexion à la base de données
$host = 'localhost';
$dbname = 'app_g8e'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur par défaut pour phpMyAdmin
$password = ''; // Mot de passe par défaut pour phpMyAdmin
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Erreur de connexion à la base de données : " . $e->getMessage());
}
if (!isset($_SESSION['user'])) {
    header('Location: panier.php?id=user');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftySquirrel - Accueil</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
     <script src="PanierSupprimer.js">
        supprimer();
        </script>
    <!-- Header intégré -->
    <?php include 'header.php'; ?>
  <main>
    <div class="cart-container">
      <div class="cart-title-bar">Mon Panier</div>
      <table class="panierachat">
      <thead>
        <tr>
          <th class='tete' scope="col">Description</th>
          <th class='tete' scope="col">Produit</th>
          <th class='tete' scope="col" >Quantité</th>
          <th class='tete' scope="col">Total</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
      <?php
      /*PRODUITS MIS DANS LE PANIER*/
      $id=filter_input(INPUT_GET,'idProduit',FILTER_SANITIZE_NUMBER_INT);
      if(!empty($id)) {
        $select_id="DELETE FROM panierachat WHERE idProduit = :idProduit";
        $stmt = $pdo->prepare($select_id);
        $stmt->bindParam(':idProduit', $id);
        if($stmt->execute()) {
           $return=['Erreur' => false, 'msg' => '<div role>Produit supprimé.</div>'];
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $return = ['Erreur' => false, 'delete' => $row];
      }else{
        $return=["'Erreur' => true, 'msg' => '<div role ='alert'>Produit non supprimé"];
      }
      echo json_decode($return);
    }
      $sql="SELECT produit.description, panierachat.idProduit, produit.nom, panierachat.quantite, (produit.prix*panierachat.quantite) AS total FROM panierachat, produit WHERE produit.nProduit=panierachat.idProduit";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
        echo "<tr>";
        echo "<th class='description'>".$row['description']."</th>" ;
        echo "<th class='valeurs'>".$row['nom']."<br></th>" ;
        echo "<th class='valeurs'>".$row['quantite']." pcs</th>";
        echo "<th class='valeurs'>".$row['total']." €</th>";
        echo "<th><form method='POST'><input type='button' id='$id' value=Supprimer class='supprimer' onclick='supprimer($row)'></button></th>";
        echo "</tr>";
      }
      /*TOTAL*/
      $totalpayer="SELECT SUM(panierachat.quantite*produit.prix) AS total2 FROM panierachat,produit WHERE produit.nProduit=panierachat.idProduit "; 
      $stmt2 = $pdo->prepare("$totalpayer");
      $stmt2->execute();
      $row3 = $stmt2->fetch(PDO::FETCH_ASSOC);
      echo "<tr><th scope='col' class='basdepage'> Total à payer: ".$row3['total2']." €</th></tr>";
      echo "<tr><th scope='col' class='payer'><a href='paiement.php'>Payer</th></tr>";
      /*SUPPRIMER PRODUIT*/
      ?>
      </tbody>
      </table>
      <style>
        .cart-title-bar{
          width: 200px;
        }
        .supprimer{
          background-color:black;
          border-top-right-radius: 5pc;
          border-bottom-right-radius: 5pc;
          color: white;
          width: 200px;
          height: 50px;
          font-weight: bold;
        }
        .total{
          background-color:black;
          color: white;
        }
        tbody{
          border-radius: 10pc;
          color: black;
        }
        .tete{
          background-color:#e9aa6f;
          border-top-left-radius: 15px;
          border-top-right-radius: 15px;
          color:white;
          padding-left: 80px;
          padding-right: 80px;
          height: 20px;
        }
        .cart-container{
          margin-left: 100px;
          align-items: center;
        }
        th{
          align-items: center;
          justify-content: center;
          padding:5px;
          width: 5px;
        }
        .description{
          font-weight: normal;
          background-color:rgba(233, 170, 111, 0.69);
          color:black;
          height: 20px;
        }
        table{
          padding: 30px;
          font-size:17px;
          border-radius:2pc;
          background-color: white;
          height: 550px;
          width: 400px;
          position: ;
        }
        .valeurs{
          background-color:rgba(233, 170, 111, 0.69);
          color:black;
          height: 20px;
        }
        .basdepage{
          background-color: #e9aa6f;
          font-size: 18px;
          border-bottom-left-radius: 1pc;
        }
        .payer{
          background-color:rgb(38, 147, 26);
          color:white;
          border-bottom-right-radius: 2pc;
          border-bottom-left-radius: 2pc;
          height: 10px;
          padding-left:5px;
          margin-left: 200px;
        }
      </style>
  </main>
</body>
<footer class="site-footer">
    <div>
        <h4>À propos de CraftySquirrel</h4>
        <p><a href="./contact.php">Contactez-nous</a></p>
        <p><a href="./contact.php">Blog</a></p>
        <p><a href="./faq.php">FAQ</a></p>
    </div>
    <div>
        <h4>CGU</h4>
        <p><a href="./Mentions.php">Mentions</a></p>
        <p><a href="./cgv.php">CGV</a></p>
        <p>Développement</p>
    </div>
    <div>
        <h4>Aide & Contacts</h4>
        <p>contact@CraftySquirrel.com</p>
        <p>28 Rue Notre Dame des Champs, Paris</p>
    </div>
</footer>
</html>
