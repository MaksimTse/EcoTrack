<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title data-i18n="title">Keskkonnaandmed - API Rakendus</title>
    <link rel="stylesheet" href="../styles/style.css" />
    <link rel="icon" type="image/png" href="../assets/EcoTrackLogo.png">
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="container">
    <header data-i18n="header">Keskkonnaandmete Portaal</header>
    <div class="burger-toggle" id="burger-toggle">☰</div>

    <nav class="desktop-nav" id="desktop-nav">
        <a href="../pages/airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="../pages/weather.php" data-i18n="nav_weather">Ilm</a>
        <a href="../pages/about.php" class="active" data-i18n="nav_about">Info</a>
        <a href="../pages/favorites.php" data-i18n="nav_favorites">Lemmikud</a>
        <a href="../api/logout.php" data-i18n="nav_logout">Logi välja</a>

    </nav>

    <nav class="mobile-nav" id="mobile-nav">
        <a href="../pages/airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="../pages/weather.php" data-i18n="nav_weather">Ilm</a>
        <a href="../pages/about.php" class="active" data-i18n="nav_about">Info</a>
        <a href="../pages/favorites.php" data-i18n="nav_favorites">Lemmikud</a>
        <a href="../api/logout.php" data-i18n="nav_logout">Logi välja</a>
    </nav>
    <div class="switchers">
        <button id="theme-toggle">🌙</button>
        <button id="lang-toggle">🇬🇧</button>
    </div>

    <main>
        <section id="about">
            <h2 data-i18n="about_title">📌 Projekti Info</h2>
            <p><strong data-i18n="about_what">Mis on Keskkonnaandmete Portaal?</strong> <span data-i18n="about_what_desc">See on kaasaegne veebirakendus...</span></p>
            <p data-i18n="about_feature1">📍 Reaalajas õhukvaliteedi andmed</p>
            <p data-i18n="about_feature2">🌡️ Live-ilmaennustused</p>
            <p data-i18n="about_feature3">📊 Lihtne ja loogiline kasutajaliides</p>
        </section>
    </main>

    <footer data-i18n="footer">
        &copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.
    </footer>
</div>

<script src="../scripts/aboutScript.js"></script>
<script src="../scripts/navScript.js"></script>
</body>
</html>
