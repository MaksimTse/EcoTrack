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
<body data-theme="light">
<div class="container">
    <header data-i18n="header">Keskkonnaandmete Portaal</header>
    <nav>
        <a href="./index.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="./weather.php" data-i18n="nav_weather">Ilm</a>
        <a href="./about.php" data-i18n="nav_about">Info</a>
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
<script src="AQIscript.js"></script>
<script>
    const themeBtn = document.getElementById('theme-toggle');
    const langBtn = document.getElementById('lang-toggle');

    function setTheme(mode) {
        document.body.dataset.theme = mode;
        localStorage.setItem('theme', mode);
        themeBtn.textContent = mode === 'dark' ? '☀️' : '🌙';
    }

    function setLanguage(lang) {
        localStorage.setItem('lang', lang);
        langBtn.textContent = lang === 'et' ? 'English' : 'Eesti';
        translatePage(lang);
    }

    const translations = {
        et: {
            header: "Keskkonnaandmete Portaal",
            nav_aqi: "Õhukvaliteet",
            nav_weather: "Ilm",
            nav_about: "Info",
            section_title: "🌍 Õhukvaliteet",
            what_is: "Mis see on?",
            what_is_desc: "Õhukvaliteedi indeks (AQI) näitab, kui puhas või saastunud on õhk. Mida madalam on AQI, seda parem on õhu kvaliteet.",
            how_work: "Kuidas see töötab?",
            how_work_desc: "Valige asukoht kaardil, et näha andmeid.",
            info_text: "👉 Valige koht kaardil, et näha andmeid.",
            disclaimer: "⚠️ NB! Kaardil kuvatavad andmed võivad olla ebatäpsed... Kui konkreetse asukoha andmed puuduvad, kuvatakse lähima mõõtepunkti teave.",
            footer: "&copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud."
        },
        en: {
            header: "Environmental Data Portal",
            nav_aqi: "Air Quality",
            nav_weather: "Weather",
            nav_about: "About",
            section_title: "🌍 Air Quality",
            what_is: "What is it?",
            what_is_desc: "The Air Quality Index (AQI) indicates how clean or polluted the air is. The lower the AQI, the better the air quality.",
            how_work: "How does it work?",
            how_work_desc: "Click a point on the map to see data.",
            info_text: "👉 Click on the map to see data.",
            disclaimer: "⚠️ Note! The data shown on the map may be inaccurate... If no data is available for the selected location, the nearest station's data is shown.",
            footer: "&copy; 2025 Environmental Data Application. All rights reserved."
        }
    };

    function translatePage(lang) {
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.dataset.i18n;
            if (translations[lang] && translations[lang][key]) {
                el.innerHTML = translations[lang][key];
            }
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        const systemPref = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedTheme = localStorage.getItem('theme') || (systemPref ? 'dark' : 'light');
        const savedLang = localStorage.getItem('lang') || 'et';
        setTheme(savedTheme);
        setLanguage(savedLang);

        themeBtn.addEventListener("click", () => {
            const newTheme = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });

        langBtn.addEventListener("click", () => {
            const currentLang = localStorage.getItem('lang') || 'et';
            const newLang = currentLang === 'et' ? 'en' : 'et';
            setLanguage(newLang);
        });
    });
</script>
</body>
</html>
