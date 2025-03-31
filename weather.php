<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Ilmakaart - Keskkonnaandmed</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <header>Keskkonnaandmete Portaal</header>
    <nav>
        <a href="./index.php">õhukvaliteet</a>
        <a href="./weather.html" class="active">Ilm</a>
        <a href="./about.php">Info</a>
    </nav>
    <main>
        <section id="weather">
            <h2>🌦️ Ilmaandmed</h2>
            <p><strong>Mis see on?</strong> Kaardipõhine ilmarakendus, mis näitab reaalajas temperatuuri ja tuuleinfot valitud punktist.</p>
            <p><strong>Kuidas see töötab?</strong> Vajuta kaardil punktile, et näha infot. Allpool näed tuulekaarti.</p>
            <div id="map"></div>
            <div id="weather-info">⏳ Laen ilmaandmeid...</div>
        </section>

        <section id="windmap">
            <h2>💨 Interaktiivne Tuulekaart</h2>
            <iframe src="https://embed.windy.com/embed2.html" width="100%" height="500" frameborder="0"></iframe>
        </section>

    </main>
    <footer>
        &copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.
    </footer>
</div>
<script src="weather.js"></script>
</body>
</html>
