const showLoginBtn = document.getElementById('show-login');
const showRegisterBtn = document.getElementById('show-register');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const themeToggle = document.getElementById('theme-toggle');
const langToggle = document.getElementById('lang-toggle');

const translations = {
    et: {
        login: "Logi sisse",
        register: "Registreeru",
        emailPlaceholder: "E-mail",
        passwordPlaceholder: "Parool",
        loginTitle: "Logi sisse",
        registerTitle: "Loo konto",
        loginButton: "Logi sisse",
        registerButton: "Registreeru"
    },
    en: {
        login: "Login",
        register: "Register",
        emailPlaceholder: "Email",
        passwordPlaceholder: "Password",
        loginTitle: "Login",
        registerTitle: "Create Account",
        loginButton: "Login",
        registerButton: "Register"
    }
};

function getLang() {
    return localStorage.getItem('lang') || 'et';
}

function showMessage(message, type = 'success') {
    const box = document.getElementById('message-box');
    box.textContent = message;
    box.className = `message-box ${type}`;
    box.style.display = 'block';

    setTimeout(() => {
        box.style.display = 'none';
    }, 4000);
}

function translateForms() {
    const lang = getLang();
    document.getElementById('login-email').placeholder = translations[lang].emailPlaceholder;
    document.getElementById('login-password').placeholder = translations[lang].passwordPlaceholder;
    document.getElementById('register-email').placeholder = translations[lang].emailPlaceholder;
    document.getElementById('register-password').placeholder = translations[lang].passwordPlaceholder;
    document.getElementById('login-title').textContent = translations[lang].loginTitle;
    document.getElementById('register-title').textContent = translations[lang].registerTitle;
    document.getElementById('login-submit').textContent = translations[lang].loginButton;
    document.getElementById('register-submit').textContent = translations[lang].registerButton;
    showLoginBtn.textContent = translations[lang].login;
    showRegisterBtn.textContent = translations[lang].register;
}

showLoginBtn.addEventListener('click', () => {
    loginForm.classList.add('active');
    registerForm.classList.remove('active');
    showLoginBtn.classList.add('active');
    showRegisterBtn.classList.remove('active');
});

showRegisterBtn.addEventListener('click', () => {
    registerForm.classList.add('active');
    loginForm.classList.remove('active');
    showRegisterBtn.classList.add('active');
    showLoginBtn.classList.remove('active');
});

themeToggle.addEventListener('click', () => {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', newTheme);
    themeToggle.textContent = newTheme === 'dark' ? '☀️' : '🌙';
    localStorage.setItem('theme', newTheme);
});

langToggle.addEventListener('click', () => {
    const currentLang = getLang();
    const newLang = currentLang === 'et' ? 'en' : 'et';
    localStorage.setItem('lang', newLang);
    translateForms();
    langToggle.textContent = newLang === 'et' ? 'EN' : 'EE';
});

window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
    themeToggle.textContent = savedTheme === 'dark' ? '☀️' : '🌙';

    translateForms();
    const lang = getLang();
    langToggle.textContent = lang === 'et' ? 'EN' : 'EE';
});

document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    const response = await fetch('../api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ email, password })
    });

    const data = await response.json();
    if (response.ok) {
        showMessage(data.message, 'success');
        setTimeout(() => {
            window.location.href = '../pages/airquality.php';
        }, 1000);
    } else {
        showMessage(data.error, 'error');
    }
});

document.getElementById('register-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const username = document.getElementById('register-username').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;

    try {
        const registerResponse = await fetch('../api/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ username, email, password })
        });

        const registerData = await registerResponse.json();

        if (registerResponse.ok) {
            showMessage(registerData.message, 'success');

            await new Promise(resolve => setTimeout(resolve, 500));

            const loginResponse = await fetch('../api/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ email, password })
            });

            const loginData = await loginResponse.json();
            if (loginResponse.ok) {
                showMessage(loginData.message, 'success');
                setTimeout(() => {
                    window.location.href = '../pages/airquality.php';
                }, 1000);
            } else {
                showMessage(loginData.error || 'Login failed', 'error');
            }

        } else {
            showMessage(registerData.error, 'error');
        }
    } catch (err) {
        console.error(err);
        showMessage('❌ Server error!', 'error');
    }
});

