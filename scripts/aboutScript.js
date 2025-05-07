const translations = {
    et: {
        title: "Keskkonnaandmed - API Rakendus",
        header: "Keskkonnaandmete Portaal",
        nav_aqi: "Õhukvaliteet",
        nav_weather: "Ilm",
        nav_about: "Info",
        nav_logout: "Logi Välja",
        nav_favorites: "Lemmikud",
        about_title: "Projekti Info",
        about_what: "Mis on Keskkonnaandmete Portaal?",
        about_what_desc: "See on kaasaegne veebirakendus...",
        about_feature1: "📍 Reaalajas õhukvaliteedi andmed",
        about_feature2: "🌡️ Live-ilmaennustused",
        about_feature3: "📊 Lihtne ja loogiline kasutajaliides",
        footer: "&copy; 2025 Keskkonnaandmete Rakendus. Kõik õigused kaitstud."
    },
    en: {
        title: "Environmental Data - API App",
        header: "Environmental Data Portal",
        nav_aqi: "Air Quality",
        nav_weather: "Weather",
        nav_about: "Info",
        nav_logout: "Logout",
        nav_favorites: "Favorites",
        about_title: "Project Info",
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