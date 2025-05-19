<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Nutwork</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <?php include 'db_connection.php'; ?>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nom = $_POST['nom'] ?? '';
      $prenom = $_POST['prenom'] ?? '';
      $email = $_POST['email'] ?? '';
      $sujet = $_POST['sujet'] ?? '';
      $message = $_POST['message'] ?? '';

      $stmt = $pdo->prepare("INSERT INTO support_ticket (nom, prenom, email, sujet, message) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$nom, $prenom, $email, $sujet, $message]);
      $success = true;
  }
  ?>

  <main>
    <h1 class="contact-title">Contactez-nous</h1>
    <div class="contact-form">
      <form method="post">
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom" placeholder="Nom" required>
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
          <label for="sujet">Sujet</label>
          <input type="text" id="sujet" name="sujet" placeholder="Sujet" required>
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="5" placeholder="Votre message" required></textarea>
        </div>
        <button type="submit" class="btn-submit">Envoyer</button>
        <?php if (!empty($success)): ?>
          <p>Votre message a bien été envoyé !</p>
        <?php endif; ?>
      </form>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>