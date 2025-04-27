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
</head>
<body data-theme="light">
<div class="container">
    <header data-i18n="header">Projekti Info</header>
    <nav>
        <a href="./airquality.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="./weather.php" data-i18n="nav_weather">Ilm</a>
        <a href="./about.php" class="active" data-i18n="nav_about">Info</a>
        <div class="switchers">
            <button id="theme-toggle">🌙</button>
            <button id="lang-toggle">🇬🇧</button>
        </div>
    </nav>

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

<script>
    const translations = {
        et: {
            title: "Keskkonnaandmed - API Rakendus",
            header: "Projekti Info",
            nav_aqi: "Õhukvaliteet",
            nav_weather: "Ilm",
            nav_about: "Info",
            nav_logout: "Logi Välja",
            about_title: "📌 Projekti Info",
            about_what: "Mis on Keskkonnaandmete Portaal?",
            about_what_desc: "See on kaasaegne veebirakendus...",
            about_feature1: "📍 Reaalajas õhukvaliteedi andmed",
            about_feature2: "🌡️ Live-ilmaennustused",
            about_feature3: "📊 Lihtne ja loogiline kasutajaliides",
            footer: "&copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud."
        },
        en: {
            title: "Environmental Data - API App",
            header: "Project Info",
            nav_aqi: "Air Quality",
            nav_weather: "Weather",
            nav_about: "About",
            nav_logout: "Logout",
            about_title: "📌 Project Info",
            about_what: "What is the Environmental Data Portal?",
            about_what_desc: "This is a modern web application...",
            about_feature1: "📍 Real-time air quality data",
            about_feature2: "🌡️ Live weather forecasts",
            about_feature3: "📊 Simple and intuitive UI",
            footer: "&copy; 2025 Environmental Data App. All rights reserved."
        }
    };

    window.onload = () => {
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');

        const systemPref = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedTheme = localStorage.getItem('theme') || (systemPref ? 'dark' : 'light');
        const savedLang = localStorage.getItem('lang') || 'et';

        setTheme(savedTheme);
        setLanguage(savedLang);

        themeBtn.addEventListener('click', () => {
            const newTheme = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });

        langBtn.addEventListener('click', () => {
            const currentLang = localStorage.getItem('lang') || 'et';
            const newLang = currentLang === 'et' ? 'en' : 'et';
            setLanguage(newLang);
        });

        function setTheme(mode) {
            document.body.dataset.theme = mode;
            localStorage.setItem('theme', mode);
            themeBtn.textContent = mode === 'dark' ? '☀️' : '🌙';
        }

        function setLanguage(lang) {
            localStorage.setItem('lang', lang);
            langBtn.textContent = lang === 'et' ? 'EN' : 'EE';
            translatePage(lang);
            document.title = translations[lang].title;
        }

        function translatePage(lang) {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.dataset.i18n;
                if (translations[lang] && translations[lang][key]) {
                    el.innerHTML = translations[lang][key];
                }
            });
        }
    };
</script>
</body>
</html>
