<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftySquirrel - Accueil</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        html { scroll-behavior: smooth; }
        .page-nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            background-color: #fdf5ed;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #ddd;
        }
        .page-nav a {
            color: #8C5A2B;
            text-decoration: none;
            font-weight: 600;
        }
        .page-nav a:hover {
            text-decoration: underline;
        }
        section:nth-of-type(odd) { background-color: #fff; }
        section:nth-of-type(even) { background-color: #fff3eb; }
        .about-grid div,
        .feature,
        .review-card,
        .member {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .about-grid div:hover,
        .feature:hover,
        .review-card:hover,
        .member:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Header -->
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="hero-section" id="hero">
    <h1>Exposez votre savoir-faire</h1>
    <p>CraftySquirrel est la vitrine digitale idéale pour les artisans passionnés. Rejoignez une communauté qui valorise l'authenticité, la qualité et l'artisanat local.</p>
    <button class="cta-button" onclick="location.href='contact.php'">Rejoindre CraftySquirrel</button>
</section>

<!-- Nos Services -->
<section class="quick-links" id="services">
    <h2>Nos services</h2>
    <div class="quick-links-grid">
        <a href="./Inscription.php" class="quick-link-card">Création de boutique</a>
        <a href="./contact.php" class="quick-link-card">Assistance personnalisée</a>
        <a href="./faq.php" class="quick-link-card">Questions fréquentes</a>
        <a href="./Inscription.php" class="quick-link-card">Inscription rapide</a>
    </div>
</section>

<!-- À PROPOS -->
<section class="about" id="about">
    <h2>Qui sommes-nous ?</h2>
    <p>CraftySquirrel est une plateforme digitale conçue pour les commerçants et artisans locaux. Nous vous accompagnons pour développer votre vitrine numérique en toute simplicité.</p>
    <div class="about-grid">
        <div>
            <h3>💼 Pour les pros</h3>
            <p>Interface de gestion intuitive pour gérer vos produits et commandes facilement.</p>
        </div>
        <div>
            <h3>📈 Croissance</h3>
            <p>Statistiques avancées et outils marketing pour améliorer vos performances.</p>
        </div>
        <div>
            <h3>🤝 Communauté</h3>
            <p>Rejoignez un réseau d’entrepreneurs passionnés et échangez vos savoir-faire.</p>
        </div>
    </div>
</section>

<!-- Avantages -->
<section class="features" id="features">
    <div class="feature">
        <h3>Visibilité augmentée</h3>
        <p>Profitez d'une présence en ligne forte grâce à notre plateforme optimisée pour le référencement.</p>
    </div>
    <div class="feature">
        <h3>Support personnalisé</h3>
        <p>Bénéficiez d'un accompagnement dédié pour mettre en valeur vos produits et votre histoire.</p>
    </div>
    <div class="feature">
        <h3>Communauté engagée</h3>
        <p>Rejoignez un réseau d'artisans partageant les mêmes valeurs de passion et d'excellence.</p>
    </div>
</section>

<!-- Actualités -->
<section class="news-section">
    <h2>Actualités Nutwork</h2>
    <ul>
        <li><strong>Avril 2025 :</strong> Lancement du tableau de bord des ventes pour commerçants.</li>
        <li><strong>Mars 2025 :</strong> Partenariat avec les artisans du Sud-Ouest.</li>
        <li><strong>Février 2025 :</strong> Nouvelle interface mobile plus rapide !</li>
    </ul>
</section>

<!-- Retour client -->
<section class="reviews" id="reviews">
    <div class="review-card">
        <h4>Avis</h4>
        <p>Super !</p>
        <p>☆☆☆☆☆</p>
        <p>Avi</p>
    </div>
    <div class="review-card">
        <h4>Avis</h4>
        <p>Interface simple et intuitive, merci 🤩</p>
        <p>☆☆☆☆☆</p>
        <p>Hugo</p>
    </div>
    <div class="review-card">
        <h4>Avis</h4>
        <p>👍🏼</p>
        <p>☆☆☆☆☆</p>
        <p>Sarah</p>
    </div>
</section>

<!-- ÉQUIPE -->
<section class="team" id="team">
    <h2>Notre Équipe</h2>
    <div class="team-grid">
        <div class="member">
            <img src="assets/images/Djelloul.png" alt="Djelloul">
            <h4>DERNI</h4>
            <h4>Djelloul</h4>
            <p>Designer</p>
        </div>
        <div class="member">
            <img src="assets/images/Deng.jpg" alt="Guokuang">
            <h4>DENG</h4>
            <h4>Guokuang</h4>
            <p>Manager</p>
        </div>
        <div class="member">
            <img src="assets/images/Huang.jpg" alt="Zijie">
            <h4>HUANG</h4>
            <h4>Zijie</h4>
            <p>CEO</p>
        </div>
        <div class="member">
            <img src="assets/images/Nascimento.jpg" alt="Helton">
            <h4>NASCIMENTO</h4>
            <h4>Helton</h4>
            <p>Développeur Full-Stack</p>
        </div>
        <div class="member">
            <img src="assets/images/Arthur.png" alt="Arthur">
            <h4>BOUTIN</h4>
            <h4>Arthur</h4>
            <p>Développeur Full-Stack</p>
        </div>
        <div class="member">
            <img src="assets/images/Mbono.jpg" alt="Gratien">
            <h4>MBONO IKA</h4>
            <h4>Gratien</h4>
            <p>SAV</p>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
</body>
</html>
