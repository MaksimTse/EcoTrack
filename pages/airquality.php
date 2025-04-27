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
    <title>Keskkonnaandmed - API Rakendus</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="../styles/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-theme="light">
<div class="container">
    <header data-i18n="header">Keskkonnaandmete Portaal</header>
    <nav>
        <a href="../pages/airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="../pages/weather.php" data-i18n="nav_weather">Ilm</a>
        <a href="../pages/about.php" data-i18n="nav_about">Info</a>
        <a href="../api/logout.php" data-i18n="nav_logout">Logi välja</a>
        <div class="switchers">
            <button id="theme-toggle">🌙</button>
            <button id="lang-toggle">🇬🇧</button>
        </div>
    </nav>
    <main>
        <section id="air-quality">
            <h2><span data-i18n="section_title">🌍 Õhukvaliteet</span></h2>
            <p><strong data-i18n="what_is">Mis see on?</strong> <span data-i18n="what_is_desc">Õhukvaliteedi indeks (AQI) näitab, kui puhas või saastunud on õhk. Mida madalam on AQI, seda parem on õhu kvaliteet.</span></p>
            <p><strong data-i18n="how_work">Kuidas see töötab?</strong> <span data-i18n="how_work_desc">Valige asukoht kaardil, et näha andmeid.</span></p>

            <div id="map"></div>
            <div id="info"><strong data-i18n="info_text">👉 Valige koht kaardil, et näha andmeid.</strong></div>
            <p class="disclaimer" data-i18n="disclaimer">⚠️ NB! Kaardil kuvatavad andmed võivad olla ebatäpsed... Kui konkreetse asukoha andmed puuduvad, kuvatakse lähima mõõtepunkti teave.</p>
        </section>
    </main>

    <footer data-i18n="footer">
        &copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.
    </footer>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="../scripts/AQIscript.js"></script>
</body>
</html>
