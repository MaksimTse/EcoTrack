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
    <title data-i18n="title">Ilmakaart - Keskkonnaandmed</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="../styles/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/EcoTrackLogo.png">
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
</head>
<body>
<div id="notification" class="notification"></div>
<div class="container">
    <header data-i18n="header">Keskkonnaandmete Portaal</header>
    <div class="burger-toggle" id="burger-toggle">☰</div>

    <nav class="desktop-nav" id="desktop-nav">
        <a href="../pages/airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="../pages/weather.php" class="active" data-i18n="nav_weather">Ilm</a>
        <a href="../pages/about.php" data-i18n="nav_about">Info</a>
        <a href="../pages/favorites.php" data-i18n="nav_favorites">Lemmikud</a>
        <a href="../api/logout.php" data-i18n="nav_logout">Logi välja</a>

    </nav>

    <nav class="mobile-nav" id="mobile-nav">
        <a href="../pages/airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="../pages/weather.php" class="active" data-i18n="nav_weather">Ilm</a>
        <a href="../pages/about.php" data-i18n="nav_about">Info</a>
        <a href="../pages/favorites.php" data-i18n="nav_favorites">Lemmikud</a>
        <a href="../api/logout.php" data-i18n="nav_logout">Logi välja</a>
    </nav>
    <div class="switchers">
        <button id="theme-toggle">🌙</button>
        <button id="lang-toggle">🇬🇧</button>
    </div>

    <main>
        <section id="weather">
            <h2 data-i18n="weather_section_title">🌦️ Ilmaandmed</h2>
            <p><strong data-i18n="weather_what">Mis see on?</strong> <span data-i18n="weather_what_desc">Kaardipõhine ilmarakendus, mis näitab reaalajas temperatuuri ja tuuleinfot valitud punktist.</span></p>
            <p><strong data-i18n="weather_how">Kuidas see töötab?</strong> <span data-i18n="weather_how_desc">Vajuta kaardil punktile, et näha infot. Allpool näed tuulekaarti.</span></p>
            <div id="map"></div>
            <div id="weather-info" data-i18n="weather_loading">⏳ Laen ilmaandmeid...</div>
            <div class="date-range">
                <label><span data-i18n="weather_from">Alates:</span> <input type="date" id="start-date"></label>
                <label><span data-i18n="weather_to">Kuni:</span> <input type="date" id="end-date"></label>
            </div>
        </section>

        <section id="windmap">
            <h2 data-i18n="windmap_title">💨 Interaktiivne Tuulekaart</h2>
            <iframe src="https://embed.windy.com/embed2.html" width="100%" height="500" frameborder="0"></iframe>
        </section>
    </main>
    <footer data-i18n="footer">
        &copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.
    </footer>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../scripts/weatherScript.js"></script>
<script src="../scripts/navScript.js"></script>
</body>
</html>
