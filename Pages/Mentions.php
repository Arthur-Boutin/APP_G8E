<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mentions légales - Nutwork</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include 'header.php'; ?>

  <div class="main-container">
    <h1>Mentions légales</h1>

    <p>
      Tous les sites internet professionnels doivent afficher des mentions obligatoires
      pour l’information du public. L’absence de ces informations sur le site est sanctionnée.
    </p>

    <div class="legal-list">
      <div class="legal-item">
        <div class="legal-question">
          <img class="left-icon" src="./assets/images/icons8-right-arrow-30.png" alt="Right Arrow">
          <h3>L’identification des responsables du site</h3>
          <img class="right-icon" src="./assets/images/icons8-chevron-down-50.png" alt="Toggle">
        </div>

        <div class="legal-answer">
          <p>Vous devez clairement indiquer le nom ou la raison sociale, l’adresse, le numéro de téléphone,<p>
          <p>le numéro SIRET ou tout autre élément permettant d’identifier le responsable légal du site.<p>
        </div>
      </div>

      <div class="legal-item">
        <div class="legal-question">
          <img class="left-icon" src="./assets/images/icons8-right-arrow-30.png" alt="Right Arrow">
          <h3>L’activité du professionnel</h3>
          <img class="right-icon" src="./assets/images/icons8-chevron-down-50.png" alt="Toggle">
        </div>

        <div class="legal-answer">
          <p>Il est nécessaire de détailler les informations relatives à l'activité exercée<p>
          <p>(prestation de service, vente de produits...), ainsi que toute condition légale<p>
          <p> relative à cette activité (inscription au Registre du Commerce, etc.).</p>
        </div>
      </div>

      <div class="legal-item">
        <div class="legal-question">
          <img class="left-icon" src="./assets/images/icons8-right-arrow-30.png" alt="Right Arrow">
          <h3>L’utilisation des cookies</h3>
          <img class="right-icon" src="./assets/images/icons8-chevron-down-50.png" alt="Toggle">
        </div>
        <div class="legal-answer">
          <p>Vous devez informer clairement l’utilisateur de la présence de cookies, de leur finalité<p>
          <p> (analyse, publicité, etc.) et de la manière dont il peut les accepter ou les refuser.</p>
        </div>
      </div>

      <div class="legal-item">
        <div class="legal-question">
          <img class="left-icon" src="./assets/images/icons8-right-arrow-30.png" alt="Right Arrow">
          <h3>L’utilisation des données personnelles</h3>
          <img class="right-icon" src="./assets/images/icons8-chevron-down-50.png" alt="Toggle">
        </div>
        <div class="legal-answer">
          <p>Les obligations RGPD imposent de mentionner le responsable du traitement,<p>
          <p>la finalité de la collecte, la durée de conservation, ainsi que les droits<p>
          <p>des utilisateurs (accès, rectification, suppression, etc.).</p>
        </div>
      </div>
    </div>
  </div>

  <div style="flex-grow: 1;"></div> <!-- La div vide nouvellement ajoutée repousse automatiquement le pied de page au bas de l'écran. -->

  <?php include 'footer.php'; ?>
  <script src="./script.js"></script>
</body>
</html>