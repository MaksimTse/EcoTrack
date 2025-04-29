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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minu Lemmikud</title>
    <link rel="stylesheet" href="../styles/favorites.css">
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body data-theme="light">
<div class="container">
    <header data-i18n="header">Keskkonnaandmete Portaal</header>
    <nav>
        <a href="../pages/airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="../pages/weather.php" data-i18n="nav_weather">Ilm</a>
        <a href="../pages/about.php" data-i18n="nav_about">Info</a>
        <a href="../pages/favorites.php" class="active" data-i18n="nav_favorites">Lemmikud</a>
        <a href="../api/logout.php" data-i18n="nav_logout">Logi välja</a>
        <div class="switchers">
            <button id="theme-toggle">🌙</button>
            <button id="lang-toggle">EN</button>
        </div>
    </nav>

    <main>
        <h2 data-i18n="favorites_title">⭐ Minu Lemmikud</h2>
        <div class="favorites-container">
            <div class="favorites-block">
                <h3 data-i18n="favorites_aqi">🌫️ Õhukvaliteedi Lemmikud</h3>
                <div id="favorites-aqi-list"></div>
            </div>
            <div class="favorites-block">
                <h3 data-i18n="favorites_weather">🌦️ Ilma Lemmikud</h3>
                <div id="favorites-weather-list"></div>
            </div>
        </div>
    </main>

    <footer data-i18n="footer">© 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.</footer>
</div>
<script src="../scripts/favoritesScript.js"></script>
</body>
</html>
