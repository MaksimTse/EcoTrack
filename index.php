<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Keskkonnaandmed - API Rakendus</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <header>Keskkonnaandmete Portaal</header>
    <nav>
        <a href="./index.php">Õhukvaliteet</a>
        <a href="./weather.php">Ilm</a>
        <a href="./about.php">Info</a>
    </nav>
    <main>
        <section id="air-quality">
            <h2>🌍 Õhukvaliteet</h2>
            <p><strong>Mis see on?</strong> Õhukvaliteedi indeks (AQI) näitab, kui puhas või saastunud on õhk. Mida madalam on AQI, seda parem on õhu kvaliteet.</p>
            <p><strong>Kuidas see töötab?</strong> Valige asukoht kaardil, et näha andmeid.</p>

            <div id="map"></div>
            <div id="info"><strong>👉 Valige koht kaardil, et näha andmeid.</strong></div>
            <p class="disclaimer">⚠️ NB! Kaardil kuvatavad andmed võivad olla ebatäpsed... Kui konkreetse asukoha andmed puuduvad, kuvatakse lähima mõõtepunkti teave.</p>
        </section>
    </main>

    <footer>
        &copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.
    </footer>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="AQIscript.js"></script>
</body>
</html>
