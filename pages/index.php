<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - Login/Register</title>
    <link rel="stylesheet" href="../styles/auth.css">
</head>
<body data-theme="light">

<div class="auth-container">
    <h1>EcoTrack</h1>

    <div class="switcher">
        <button id="show-login" class="active">Logi sisse</button>
        <button id="show-register">Registreeru</button>
    </div>

    <div class="switchers">
        <button id="theme-toggle">🌙</button>
        <button id="lang-toggle">🇬🇧</button>
    </div>

    <div class="form-wrapper">
        <form id="login-form" class="active">
            <h2 id="login-title">Logi sisse</h2>
            <input type="email" id="login-email" placeholder="E-mail" required>
            <input type="password" id="login-password" placeholder="Parool" required>
            <button type="submit" id="login-submit">Logi sisse</button>
        </form>

        <form id="register-form">
            <h2 id="register-title">Loo konto</h2>
            <input type="text" id="register-username" placeholder="Kasutajanimi" required>
            <input type="email" id="register-email" placeholder="E-mail" required>
            <input type="password" id="register-password" placeholder="Parool" required>
            <button type="submit" id="register-submit">Registreeru</button>
        </form>
    </div>
    <div id="message-box" class="message-box"></div>
</div>

<script src="../scripts/auth.js"></script>
</body>
</html>
