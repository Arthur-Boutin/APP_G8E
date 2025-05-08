<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paiement - Nutwork</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<?php include 'header.php'; ?>

  <div class="payment-container">
    <div class="payment-title-bar">Paiement</div>

    <div class="payment-form">
      <h3>Coordonnées de la carte</h3>
      <div class="form-row">
        <div class="form-group">
          <label for="cardnumber">Numéro de carte</label>
          <input type="text" id="cardnumber" placeholder="XXXX XXXX XXXX XXXX"/>
        </div>
        <div class="form-group">
          <label for="cardname">Nom</label>
          <input type="text" id="cardname" placeholder="Votre nom"/>
        </div>
        <div class="form-group">
          <label for="cardcvv">CVV</label>
          <input type="text" id="cardcvv" placeholder="123"/>
        </div>
      </div>
    </div>

    <div class="payment-form">
      <h3>Adresse de livraison</h3>
      <div class="form-row">
        <div class="form-group">
          <label for="fullname">Nom complet</label>
          <input type="text" id="fullname"/>
        </div>
        <div class="form-group">
          <label for="address">Adresse</label>
          <input type="text" id="address"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="city">Ville</label>
          <input type="text" id="city"/>
        </div>
        <div class="form-group">
          <label for="postcode">Code postal</label>
          <input type="text" id="postcode"/>
        </div>
      </div>
    </div>

    <a href="./ConfirmationPaiement.php" class="btn-pay">Payer maintenant</a>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
