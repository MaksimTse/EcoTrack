const token = '2701d75dedf0221c9c00d132963e523aa8d1061d';
const locationIQToken = 'pk.5ab5cc1654c32f44a88470d21764fc02';
const mapboxToken = 'sk.eyJ1Ijoib3Zlcm93bCIsImEiOiJjbTd5dXNydm4wYm5iMmlyNnQzM29zODhyIn0.a0-LkE_EuopN6DFKM9UCyA';

const themeBtn = document.getElementById('theme-toggle');
const langBtn = document.getElementById('lang-toggle');

function setTheme(mode) {
    document.body.dataset.theme = mode;
    localStorage.setItem('theme', mode);
    themeBtn.textContent = mode === 'dark' ? '☀️' : '🌙';
}

function setLanguage(lang) {
    localStorage.setItem('lang', lang);
    langBtn.textContent = lang === 'et' ? 'EN' : 'EE';
    translatePage(lang);
}

const translations = {
    et: {
        header: "Keskkonnaandmete Portaal",
        nav_aqi: "Õhukvaliteet",
        nav_weather: "Ilm",
        nav_about: "Info",
        nav_logout: "Logi Välja",
        section_title: "🌍 Õhukvaliteet",
        what_is: "Mis see on?",
        what_is_desc: "Õhukvaliteedi indeks (AQI) näitab, kui puhas või saastunud on õhk. Mida madalam on AQI, seda parem on õhu kvaliteet.",
        how_work: "Kuidas see töötab?",
        how_work_desc: "Valige asukoht kaardil, et näha andmeid.",
        info_text: "👉 Valige koht kaardil, et näha andmeid.",
        disclaimer: "⚠️ NB! Kaardil kuvatavad andmed võivad olla ebatäpsed... Kui konkreetse asukoha andmed puuduvad, kuvatakse lähima mõõtepunkti teave.",
        footer: "&copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.",
        loading_data: "⏳ Laen andmeid...",
        error_loading: "⚠️ Andmete laadimine ebaõnnestus.",
        error_fetch: "❌ Viga andmete laadimisel.",
        air_quality_very_good: "Väga hea",
        air_quality_moderate: "Rahuldav",
        air_quality_unhealthy_sensitive: "Ebatervislik tundlikule rühmale",
        air_quality_unhealthy: "Ebatervislik",
        air_quality_very_unhealthy: "Väga ebatervislik",
        air_quality_hazardous: "Ohtlik"
    },
    en: {
        header: "Environmental Data Portal",
        nav_aqi: "Air Quality",
        nav_weather: "Weather",
        nav_about: "About",
        nav_logout: "Logout",
        section_title: "🌍 Air Quality",
        what_is: "What is it?",
        what_is_desc: "The Air Quality Index (AQI) indicates how clean or polluted the air is. The lower the AQI, the better the air quality.",
        how_work: "How does it work?",
        how_work_desc: "Click a point on the map to see data.",
        info_text: "👉 Click on the map to see data.",
        disclaimer: "⚠️ Note! The data shown on the map may be inaccurate... If no data is available for the selected location, the nearest station's data is shown.",
        footer: "&copy; 2025 Environmental Data Application. All rights reserved.",
        loading_data: "⏳ Loading data...",
        error_loading: "⚠️ Failed to load data.",
        error_fetch: "❌ Error while fetching data.",
        air_quality_very_good: "Very Good",
        air_quality_moderate: "Moderate",
        air_quality_unhealthy_sensitive: "Unhealthy for Sensitive Groups",
        air_quality_unhealthy: "Unhealthy",
        air_quality_very_unhealthy: "Very Unhealthy",
        air_quality_hazardous: "Hazardous"
    }
};

function t(key) {
    const lang = localStorage.getItem('lang') || 'et';
    return translations[lang] && translations[lang][key] ? translations[lang][key] : key;
}

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

// Карта и AQI-логика
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
    zoomOffset: -1
}).addTo(map);

let currentMarker = null;

const customIcon = L.icon({
    iconUrl: './assets/gps.png',
    iconSize: [38, 38],
    iconAnchor: [19, 38],
    popupAnchor: [0, -35]
});

async function getCountryName(lat, lon) {
    const url = `https://eu1.locationiq.com/v1/reverse?key=${locationIQToken}&lat=${lat}&lon=${lon}&format=json`;
    const res = await fetch(url);
    const data = await res.json();
    return data.address.country;
}

async function getAllStations() {
    const res = await fetch(`https://api.waqi.info/map/bounds/?latlng=-90,-180,90,180&token=${token}`);
    const data = await res.json();
    return data.data.filter(s => s.aqi !== '-' && !isNaN(s.aqi));
}

map.on('click', async function (e) {
    const lat = e.latlng.lat;
    const lon = e.latlng.lng;
    const infoBox = document.getElementById('info');
    infoBox.innerHTML = t('loading_data');

    if (currentMarker) {
        map.removeLayer(currentMarker);
    }

    try {
        const locationRes = await fetch(`https://api.waqi.info/feed/geo:${lat};${lon}/?token=${token}`);
        const locationData = await locationRes.json();

        if (locationData.status === 'ok') {
            const aqi = parseInt(locationData.data.aqi);
            const location = locationData.data.city?.name || 'Unknown location';
            const dominant = locationData.data.dominentpol || 'No info';
            const updated = locationData.data.time?.s || 'Unknown time';
            const country = await getCountryName(lat, lon);

            let quality = '';
            if (aqi <= 50) quality = t('air_quality_very_good');
            else if (aqi <= 100) quality = t('air_quality_moderate');
            else if (aqi <= 150) quality = t('air_quality_unhealthy_sensitive');
            else if (aqi <= 200) quality = t('air_quality_unhealthy');
            else if (aqi <= 300) quality = t('air_quality_very_unhealthy');
            else quality = t('air_quality_hazardous');

            const stations = await getAllStations();
            const sortedByAQI = stations.slice().sort((a, b) => parseInt(a.aqi) - parseInt(b.aqi));

            const cleaner = sortedByAQI.filter(s => parseInt(s.aqi) < aqi).slice(-5).reverse();
            const dirtier = sortedByAQI.filter(s => parseInt(s.aqi) > aqi).slice(0, 5);
            const rank = sortedByAQI.findIndex(s => parseInt(s.aqi) === aqi) + 1;

            const listHTML = arr => arr.map(s => `<p>${s.station.name} – AQI ${s.aqi}</p>`).join('');

            const popupContent = `
                <div style="text-align: center; font-size: 16px;">
                    <b>📍 ${location}</b><br>
                    🌫️ AQI: <span style="color: ${aqi > 100 ? 'red' : 'green'}; font-weight: bold;">${aqi}</span>
                </div>
            `;

            infoBox.innerHTML = `
                <strong>📍 Location:</strong> ${location} (${country})<br>
                <strong>🌫️ AQI:</strong> ${aqi} (${quality})<br>
                <strong>💨 Main pollutant:</strong> ${dominant}<br>
                <strong>📅 Last updated:</strong> ${updated}<br><br>
                <strong>📊 AQI world rank:</strong> ${rank}/${sortedByAQI.length}<br><br>
                <strong>⬇️ 5 cleaner places by AQI:</strong>${listHTML(cleaner)}<br>
                <strong>🎯 Selected point:</strong>${location} (${country}) – AQI ${aqi}<br><br>
                <strong>⬆️ 5 more polluted places by AQI:</strong>${listHTML(dirtier)}
            `;

            currentMarker = L.marker([lat, lon], { icon: customIcon }).addTo(map);
            currentMarker.bindPopup(popupContent).openPopup();
        } else {
            infoBox.innerHTML = t('error_loading');
        }
    } catch (error) {
        console.error(error);
        infoBox.innerHTML = t('error_fetch');
    }
});
