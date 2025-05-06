<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil – Nutwork</title>
  <link rel="stylesheet" href="./style.css">

  <!-- correctifs locaux ----------------------------------------------------->
  <style>
    /* === SECTION PARAMÈTRE =============================================== */
    .param-form{display:flex;flex-direction:column;gap:18px}
    .param-row{display:flex;align-items:center;gap:14px}
    .param-row label{min-width:220px;font-weight:600;color:#5d3e1b}

    /* tous les champs ont la même largeur car un “emplacement-bouton” est
       présent sur chaque ligne : réel ou invisible                         */
    .param-row input{
      flex:1 1 auto;padding:8px 10px;border:1px solid #ccc;border-radius:4px}
    .btn-inline,
    .btn-placeholder{
      flex:0 0 120px;border-radius:4px;padding:8px 20px;text-align:center}
    .btn-inline{
      background:#8b5a2b;color:#fff;border:none;cursor:pointer;white-space:nowrap}
    .btn-inline:hover{filter:brightness(1.08)}
    .btn-placeholder{visibility:hidden}      /* occupe la place du bouton */

    /* on masque l’ancien bouton global                                      */
    .btn-update{display:none}
  </style>
</head>

<body>
<!-- ===========================  HEADER  ================================== -->
<header class="site-header">
  <div class="header-container">
    <div class="logo"><a href="./index.html">NUTWORK</a></div>

    <nav class="nav-menu">
      <ul>
        <li><a href="./index.html">Accueil</a></li>
        <li><a href="./articles.html">Articles</a></li>
        <li><a href="./galerie.html">Galerie</a></li>
        <li><a href="./contact.html">Contact</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <form class="search-form">
        <input class="search-bar" name="rechercher" placeholder="Rechercher…">
        <button class="search-button">🔍</button>
      </form>
      <a class="icon-link" href="./messagerie.html"><img class="icon" src="./assets/images/Mail.png"  alt=""></a>
      <a class="icon-link" href="./panier.html">     <img class="icon" src="./assets/images/truc.png"  alt=""></a>
      <a class="icon-link" href="./login.html">      <img class="icon" src="./assets/images/Profil.png" alt=""></a>
    </div>
  </div>
</header>

<!-- ===========================  MAIN  ==================================== -->
<main>
  <h1 class="page-title">Espace Personnel</h1>

  <!-- PROFIL + INFORMATION -------------------------------------------------->
  <section class="profile-section">
    <div class="profile-block">
      <h2 class="block-title">PROFIL</h2>
      <div class="profile-photo">
        <img src="./assets/images/LOGO.png" alt="Photo de profil">
        <p class="photo-label">Photo actuelle</p>
        <button class="btn btn-upload">Changer la photo</button>
      </div>
    </div>

    <div class="info-block">
      <h2 class="block-title">INFORMATION</h2>
      <form class="info-form">
        <label>NOM :</label>        <input placeholder="Entrez votre nom">
        <label>PRENOM :</label>     <input placeholder="Entrez votre prénom">
        <label>EMAIL :</label>      <input type="email" placeholder="Entrez votre email">
        <label>ADRESSE :</label>    <input placeholder="Entrez votre adresse">
        <button class="btn btn-save">Enregistrer</button>
      </form>
    </div>
  </section>

  <!-- PARAMÈTRE ------------------------------------------------------------->
  <section class="param-section">
    <h2 class="param-title">PARAMÈTRE</h2>

    <form class="param-form">
      <!-- définir mot de passe -->
      <div class="param-row">
        <label for="new-pwd">Définir un mot de passe :</label>
        <input id="new-pwd" type="password">
        <span class="btn-placeholder"></span>
      </div>

      <div class="param-row">
        <label for="new-pwd-confirm">Confirmer le mot de passe :</label>
        <input id="new-pwd-confirm" type="password">
        <button class="btn-inline" type="button">Mettre à jour</button>
      </div>

      <!-- changer mot de passe -->
      <div class="param-row">
        <label for="change-pwd">Changer le mot de passe :</label>
        <input id="change-pwd" type="password">
        <span class="btn-placeholder"></span>
      </div>

      <div class="param-row">
        <label for="change-pwd-confirm">Confirmer le mot de passe :</label>
        <input id="change-pwd-confirm" type="password">
        <button class="btn-inline" type="button">Mettre à jour</button>
      </div>
    </form>
  </section>
</main>

<!-- ===========================  FOOTER  ================================== -->
<footer class="site-footer">
  <div>
    <h4>À propos de Nutwork</h4>
    <p><a href="./contact.html">Contactez-nous</a></p>
    <p><a href="./.html">À propos de nous</a></p>
    <p><a href="./contact.html">Blog</a></p>
    <p><a href="./faq.html">FAQ</a></p>
  </div>
  <div>
    <h4>CGU</h4>
    <p><a href="./Mentions.html">Mentions</a></p>
    <p><a href="./cgv.html">CGV</a></p>
    <p>Développement</p>
  </div>
  <div>
    <h4>Aide & Contacts</h4>
    <p>contact@nutwork.com</p>
    <p>28 Rue Notre Dame des Champs, Paris</p>
  </div>
</footer>
<script src="./script.js"></script>
</body>
</html>