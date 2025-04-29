document.addEventListener('DOMContentLoaded', () => {
    const aqiList = document.getElementById('favorites-aqi-list');
    const weatherList = document.getElementById('favorites-weather-list');
    const themeBtn = document.getElementById('theme-toggle');
    const langBtn = document.getElementById('lang-toggle');

    const translations = {
        et: {
            header: "Keskkonnaandmete Portaal",
            nav_aqi: "Õhukvaliteet",
            nav_weather: "Ilm",
            nav_about: "Info",
            nav_logout: "Logi välja",
            nav_favorites: "Lemmikud",
            favorites_title: "⭐ Minu Lemmikud",
            favorites_aqi: "🌫️ Õhukvaliteedi Lemmikud",
            favorites_weather: "🌦️ Ilma Lemmikud",
            no_favorites: "Lemmikuid pole veel lisatud.",
            coordinates: "Koordinaadid",
            open_on_map: "Ava kaardil",
            remove: "Eemalda",
            footer: "&copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud.",

        },
        en: {
            header: "Environmental Data Portal",
            nav_aqi: "Air Quality",
            nav_weather: "Weather",
            nav_about: "About",
            nav_logout: "Logout",
            nav_favorites: "Favorites",
            favorites_title: "⭐ My Favorites",
            favorites_aqi: "🌫️ Air Quality Favorites",
            favorites_weather: "🌦️ Weather Favorites",
            no_favorites: "No favorites added yet.",
            coordinates: "Coordinates",
            open_on_map: "Open on map",
            remove: "Remove",
            footer: "&copy; 2025 Environmental Data Application. All rights reserved.",
        }
    };

    let currentLang = localStorage.getItem('lang') || 'et';
    const t = (key) => translations[currentLang][key] || key;

    function setTheme(mode) {
        document.body.dataset.theme = mode;
        localStorage.setItem('theme', mode);
        themeBtn.textContent = mode === 'dark' ? '☀️' : '🌙';
    }

    function setLanguage(lang) {
        currentLang = lang;
        localStorage.setItem('lang', lang);
        langBtn.textContent = lang === 'et' ? 'EN' : 'EE';
        translatePage(lang);
    }

    function translatePage(lang) {
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.dataset.i18n;
            if (translations[lang] && translations[lang][key]) {
                el.innerHTML = translations[lang][key];
            }
        });
        document.title = translations[lang].favorites_title;
    }

    function animateRemove(div) {
        div.style.transition = 'opacity 0.4s ease-out, transform 0.4s ease-out';
        div.style.opacity = '0';
        div.style.transform = 'translateX(-20px)';
        setTimeout(() => div.remove(), 400);
    }

    function loadFavorites() {
        fetch('../api/get_favorites.php', { credentials: 'include' })
            .then(res => res.json())
            .then(data => {
                aqiList.innerHTML = '';
                weatherList.innerHTML = '';

                if (data.length === 0) {
                    const msg = `<p>${t('no_favorites')}</p>`;
                    aqiList.innerHTML = msg;
                    weatherList.innerHTML = msg;
                    return;
                }

                data.forEach(fav => {
                    const div = document.createElement('div');
                    div.className = 'favorite-item';
                    const url = `${fav.source === 'AQI' ? 'airquality.php' : 'weather.php'}?lat=${fav.lat}&lon=${fav.lon}`;
                    div.innerHTML = `
                        <p><strong>${fav.location}</strong><br>
                        ${t('coordinates')}: ${fav.lat.toFixed(2)}, ${fav.lon.toFixed(2)}</p>
                        <a href="${url}" class="btn-link">${t('open_on_map')}</a>
                        <button class="remove-fav">${t('remove')}</button>
                    `;

                    div.querySelector('.remove-fav').addEventListener('click', () => {
                        fetch(`../api/remove_favorite.php?id=${fav.id}`, {
                            method: 'DELETE',
                            credentials: 'include'
                        }).then(r => {
                            if (r.ok) {
                                animateRemove(div);
                                setTimeout(() => {
                                    if (!aqiList.hasChildNodes())
                                        aqiList.innerHTML = `<p>${t('no_favorites')}</p>`;
                                    if (!weatherList.hasChildNodes())
                                        weatherList.innerHTML = `<p>${t('no_favorites')}</p>`;
                                }, 500);
                            }
                        });
                    });

                    (fav.source === 'AQI' ? aqiList : weatherList).appendChild(div);
                });
            })
            .catch(err => {
                console.error("Error loading favorites:", err);
            });
    }

    const systemPref = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('theme') || (systemPref ? 'dark' : 'light');
    setTheme(savedTheme);
    setLanguage(currentLang);

    themeBtn.addEventListener('click', () => {
        const newTheme = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    });

    langBtn.addEventListener('click', () => {
        const newLang = currentLang === 'et' ? 'en' : 'et';
        setLanguage(newLang);
        location.reload();
    });

    loadFavorites();
});
