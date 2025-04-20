<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title data-i18n="title">Ilmakaart - Keskkonnaandmed</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-theme="light">
<div class="container">
    <header data-i18n="header">Keskkonnaandmete Portaal</header>
    <nav>
        <a href="./index.php" data-i18n="nav_aqi">Õhukvaliteet</a>
        <a href="./weather.php" class="active" data-i18n="nav_weather">Ilm</a>
        <a href="./about.php" data-i18n="nav_about">Info</a>
        <div class="switchers">
            <button id="theme-toggle">🌙</button>
            <button id="lang-toggle">🇬🇧</button>
        </div>
    </nav>
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
<script>

    const translations = {
        et: {
            title: "Ilmakaart - Keskkonnaandmed",
            header: "Keskkonnaandmete Portaal",
            nav_aqi: "Õhukvaliteet",
            nav_weather: "Ilm",
            nav_about: "Info",
            weather_section_title: "🌦️ Ilmaandmed",
            weather_what: "Mis see on?",
            weather_what_desc: "Kaardipõhine ilmarakendus, mis näitab reaalajas temperatuuri ja tuuleinfot valitud punktist.",
            weather_how: "Kuidas see töötab?",
            weather_how_desc: "Vajuta kaardil punktile, et näha infot. Allpool näed tuulekaarti.",
            weather_loading: "⏳ Laen ilmaandmeid...",
            weather_from: "Alates:",
            weather_to: "Kuni:",
            windmap_title: "💨 Interaktiivne Tuulekaart",
            footer: "&copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud."
        },
        en: {
            title: "Weather Map - Environmental Data",
            header: "Environmental Data Portal",
            nav_aqi: "Air Quality",
            nav_weather: "Weather",
            nav_about: "About",
            weather_section_title: "🌦️ Weather Data",
            weather_what: "What is it?",
            weather_what_desc: "A map-based weather app showing real-time temperature and wind info at a selected location.",
            weather_how: "How does it work?",
            weather_how_desc: "Click a point on the map to see weather data. Wind map is shown below.",
            weather_loading: "⏳ Loading weather data...",
            weather_from: "From:",
            weather_to: "To:",
            windmap_title: "💨 Interactive Wind Map",
            footer: "&copy; 2025 Environmental Data Application. All rights reserved."
        }
    };
    document.addEventListener('DOMContentLoaded', () => {
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const weatherInfo = document.getElementById('weather-info');
        const startInput = document.getElementById('start-date');
        const endInput = document.getElementById('end-date');

        // === Настройка темы ===
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
            langBtn.textContent = lang === 'et' ? 'English' : 'Eesti';
            translatePage(lang);
        }

        function translatePage(lang) {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.dataset.i18n;
                if (translations[lang] && translations[lang][key]) {
                    el.innerHTML = translations[lang][key];
                }
            });-
        }

        document.body.dataset.theme = savedTheme;

        const today = new Date().toISOString().split('T')[0];
        const yesterday = new Date(Date.now() - 86400000).toISOString().split('T')[0];
        startInput.value = yesterday;
        endInput.value = today;

        startInput.addEventListener('change', validateAndUpdate);
        endInput.addEventListener('change', validateAndUpdate);

        const mapboxToken = 'sk.eyJ1Ijoib3Zlcm93bCIsImEiOiJjbTd5dXNydm4wYm5iMmlyNnQzM29zODhyIn0.a0-LkE_EuopN6DFKM9UCyA';
        const locationIQToken = 'pk.5ab5cc1654c32f44a88470d21764fc02';
        const map = L.map('map', {
            minZoom: 2,
            maxZoom: 18,
            worldCopyJump: false,
            maxBounds: [[-85, -180], [85, 180]],
            maxBoundsViscosity: 1.0
        }).setView([20, 0], 2);

        L.tileLayer(`https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=${mapboxToken}&language=en`, {
            attribution: '&copy; Mapbox',
            tileSize: 512,
            zoomOffset: -1,
            noWrap: true
        }).addTo(map);

        let currentMarker = null;

        const customIcon = L.icon({
            iconUrl: './assets/gps.png',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -35]
        });

        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            if (currentMarker) map.removeLayer(currentMarker);
            currentMarker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            validateAndUpdate();
        });

        function validateAndUpdate() {
            if (!currentMarker) return;
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const diffDays = (end - start) / (1000 * 60 * 60 * 24);

            if (diffDays > 90) {
                weatherInfo.innerHTML = '<p style="color:red;">⚠️ Palun vali vahemik, mis ei ületa 90 päeva!</p>';
                return;
            }

            const { lat, lng } = currentMarker.getLatLng();
            getWeather(lat, lng);
        }

        async function getWeather(lat, lon) {
            const start = startInput.value;
            const end = endInput.value;
            const locationInfo = await getLocationInfo(lat, lon);

            const url = `https://archive-api.open-meteo.com/v1/archive?latitude=${lat}&longitude=${lon}&start_date=${start}&end_date=${end}&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,windspeed_10m_max&timezone=auto`;

            try {
                const res = await fetch(url);
                const data = await res.json();

                const dates = data.daily.time;
                const maxTemps = data.daily.temperature_2m_max;
                const minTemps = data.daily.temperature_2m_min;
                const precs = data.daily.precipitation_sum;
                const winds = data.daily.windspeed_10m_max;

                const avgTemps = maxTemps.map((max, i) => ((max + minTemps[i]) / 2).toFixed(1));
                const currentWeatherRes = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&lang=et&appid=dd522ea0963543444d79b6d78ddc80b9`);
                const currentWeatherData = await currentWeatherRes.json();
                const condition = emojiWeather(currentWeatherData.weather[0].description);

                weatherInfo.innerHTML = `
                <h3>📍 Koordinaadid: ${lat.toFixed(2)}, ${lon.toFixed(2)}</h3>
                <p><strong>Asukoht:</strong> ${locationInfo.city}, ${locationInfo.region}, ${locationInfo.country}
                ${locationInfo.flagUrl ? `<img src="${locationInfo.flagUrl}" alt="flag" style="vertical-align: middle; margin-left: 8px;">` : ''}</p>
                <p><strong>Vahemik:</strong> ${start} → ${end}</p>
                <p><a href="${locationInfo.osmUrl}" target="_blank" style="color:#007bff; text-decoration:underline;">📌 Ava kaardil</a></p>
                <p><strong>Praegune ilm:</strong> ${condition}</p>
                <canvas id="historyChart" height="150"></canvas>
            `;

                drawChart(dates, avgTemps, precs, winds);
            } catch (err) {
                weatherInfo.innerHTML = '<p style="color:red;">❌ Ilma laadimine ebaõnnestus.</p>';
            }
        }

        async function getLocationInfo(lat, lon) {
            const url = `https://eu1.locationiq.com/v1/reverse?key=${locationIQToken}&lat=${lat}&lon=${lon}&format=json`;
            try {
                const res = await fetch(url);
                const data = await res.json();
                const countryCode = (data.address.country_code || '').toUpperCase();

                return {
                    name: data.display_name || "Tundmatu koht",
                    city: data.address.city || data.address.town || data.address.village || "—",
                    country: data.address.country || "—",
                    countryCode,
                    region: data.address.state || data.address.region || "—",
                    osmUrl: `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=10/${lat}/${lon}`,
                    flagUrl: `https://flagcdn.com/32x24/${countryCode.toLowerCase()}.png`
                };
            } catch {
                return {
                    name: "Tundmatu koht",
                    city: "—",
                    country: "—",
                    countryCode: "",
                    region: "—",
                    osmUrl: `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=10/${lat}/${lon}`,
                    flagUrl: ""
                };
            }
        }

        function emojiWeather(desc) {
            const d = desc.toLowerCase();
            if (d.includes("clear")) return "☀️ " + desc;
            if (d.includes("cloud")) return "⛅ " + desc;
            if (d.includes("rain")) return "🌧️ " + desc;
            if (d.includes("snow")) return "❄️ " + desc;
            if (d.includes("storm") || d.includes("thunder")) return "⛈️ " + desc;
            if (d.includes("mist") || d.includes("fog")) return "🌫️ " + desc;
            return "🌡️ " + desc;
        }

        function drawChart(labels, avgTemps, precs, winds) {
            const existing = Chart.getChart("historyChart");
            if (existing) existing.destroy();

            new Chart(document.getElementById("historyChart"), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Keskmine temperatuur (°C)',
                            data: avgTemps,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0,123,255,0.1)',
                            tension: 0.3
                        },
                        {
                            label: 'Sademed (mm)',
                            data: precs,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40,167,69,0.1)',
                            tension: 0.3,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Tuule kiirus (m/s)',
                            data: winds,
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255,193,7,0.1)',
                            tension: 0.3,
                            yAxisID: 'y2'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    stacked: false,
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            title: { display: true, text: '°C' }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            title: { display: true, text: 'mm' },
                            grid: { drawOnChartArea: false }
                        },
                        y2: {
                            type: 'linear',
                            position: 'right',
                            title: { display: true, text: 'm/s' },
                            grid: { drawOnChartArea: false },
                            offset: true
                        }
                    }
                }
            });
        }
    });
</script>


</body>
</html>
