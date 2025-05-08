<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié - Nutwork</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<?php include 'header.php'; ?>

  <div class="forgot-container">
    <h1>Mot de passe oublié</h1>
    <div class="forgot-box">
      <p>Veuillez saisir votre adresse mail de connexion afin de recevoir le lien de réinitialisation de votre mot de passe.</p>
      <input type="email" placeholder="Adresse mail de réinitialisation"/>
      <button class="btn">Recevoir le lien</button>
      <a href="./login.html" class="back-link">Retour à la page Connexion au compte</a>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
