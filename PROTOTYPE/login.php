<?php
session_start();

$users = [
    ["name" => "Ahmed", "password" => "admin123", "role" => "administrateur", "active" => true],
    ["name" => "Sara", "password" => "pass456", "role" => "formateur", "active" => true],
    ["name" => "Leila", "password" => "test789", "role" => "apprenant", "active" => false],
    ["name" => "Alae", "password" => "test309", "role" => "apprenant", "active" => true]
];

$message = "";

if (isset($_POST["username"]) && isset($_POST["password"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $found = false;

    foreach ($users as $user) {

        if ($user["name"] == $username && $user["password"] == $password) {

            $found = true;

            if ($user["active"] == false) {
                $message = "Compte désactivé";
            } else {
                $_SESSION["user"] = $user;
                header("Location: dashboard.php");
                exit();
            }
        }
    }

    if ($found == false) {
        $message = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Espace Sécurisé</title>
    <meta name="description" content="Connectez-vous à votre espace sécurisé pour accéder au tableau de bord.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>

      :root {
        --bg-base: #08080f;
        --bg-surface: rgba(255, 255, 255, 0.04);
        --bg-surface-hover: rgba(255, 255, 255, 0.07);
        --bg-card: rgba(255, 255, 255, 0.03);

        --accent-1: #7c5cfc;
        --accent-2: #f0736e;
        --accent-3: #3eddc6;
        --accent-glow: rgba(124, 92, 252, 0.4);

        --text-primary: #eeeef5;
        --text-secondary: #8888a4;
        --text-muted: #5e5e78;

        --border-subtle: rgba(255, 255, 255, 0.06);
        --border-accent: rgba(124, 92, 252, 0.3);

        --radius-sm: 10px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --radius-full: 999px;

        --shadow-card: 0 8px 40px rgba(0, 0, 0, 0.4);
        --shadow-glow: 0 0 80px -20px var(--accent-glow);

        --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-smooth: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }

      *, *::before, *::after {
        margin: 0; padding: 0; box-sizing: border-box;
      }

      html { scroll-behavior: smooth; }

      body {
        font-family: 'Outfit', sans-serif;
        color: var(--text-primary);
        background: var(--bg-base);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
      }

      body::before,
      body::after {
        content: '';
        position: fixed;
        border-radius: 50%;
        filter: blur(120px);
        z-index: 0;
        pointer-events: none;
        will-change: transform;
      }

      body::before {
        width: 550px; height: 550px;
        background: radial-gradient(circle, rgba(124, 92, 252, 0.2), transparent 70%);
        top: -200px; left: -150px;
        animation: orbFloat1 18s ease-in-out infinite alternate;
      }

      body::after {
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(240, 115, 110, 0.14), transparent 70%);
        bottom: -150px; right: -100px;
        animation: orbFloat2 22s ease-in-out infinite alternate;
      }

      .bg-orb {
        position: fixed;
        width: 350px; height: 350px;
        border-radius: 50%;
        filter: blur(130px);
        z-index: 0;
        pointer-events: none;
        background: radial-gradient(circle, rgba(62, 221, 198, 0.12), transparent 70%);
        top: 60%; left: 60%;
        animation: orbFloat3 20s ease-in-out infinite alternate;
      }

      @keyframes orbFloat1 {
        0%   { transform: translate(0, 0) scale(1); }
        50%  { transform: translate(100px, 80px) scale(1.15); }
        100% { transform: translate(-40px, 140px) scale(0.95); }
      }
      @keyframes orbFloat2 {
        0%   { transform: translate(0, 0) scale(1); }
        50%  { transform: translate(-80px, -60px) scale(1.1); }
        100% { transform: translate(50px, -100px) scale(0.9); }
      }
      @keyframes orbFloat3 {
        0%   { transform: translate(-50%, -50%) scale(1); }
        100% { transform: translate(-30%, -70%) scale(1.2); }
      }

      .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 440px;
        padding: 20px;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
      }

      @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(40px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
      }

      .login-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-lg);
        padding: 48px 40px;
        backdrop-filter: blur(40px) saturate(1.2);
        -webkit-backdrop-filter: blur(40px) saturate(1.2);
        box-shadow: var(--shadow-card), var(--shadow-glow);
        position: relative;
        overflow: hidden;
      }

      .login-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-1), var(--accent-2), var(--accent-3));
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
      }

      .login-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(ellipse at 50% -20%, rgba(124, 92, 252, 0.06), transparent 60%);
        pointer-events: none;
      }

      .login-header {
        text-align: center;
        margin-bottom: 36px;
        position: relative;
        z-index: 1;
      }

      .login-icon {
        width: 64px; height: 64px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 30px rgba(124, 92, 252, 0.3);
        animation: iconPulse 3s ease-in-out infinite;
      }

      @keyframes iconPulse {
        0%, 100% { box-shadow: 0 8px 30px rgba(124, 92, 252, 0.3); }
        50%      { box-shadow: 0 8px 40px rgba(124, 92, 252, 0.5); }
      }

      .login-icon svg {
        width: 28px; height: 28px;
        color: #fff;
      }

      .login-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        background: linear-gradient(135deg, #fff 30%, var(--accent-3));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
      }

      .login-header p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 300;
      }

      .form-group {
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
      }

      .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
      }

      .input-wrapper {
        position: relative;
      }

      .input-wrapper svg {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px; height: 18px;
        color: var(--text-muted);
        pointer-events: none;
        transition: color var(--transition-fast);
      }

      .form-input {
        width: 100%;
        padding: 14px 18px 14px 48px;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 400;
        background: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        outline: none;
        transition: all var(--transition-fast);
      }

      .form-input::placeholder {
        color: var(--text-muted);
      }

      .form-input:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 3px rgba(124, 92, 252, 0.12);
        background: var(--bg-surface-hover);
      }

      .form-input:focus ~ svg,
      .input-wrapper:focus-within svg {
        color: var(--accent-1);
      }

      .btn-login {
        width: 100%;
        padding: 15px;
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #fff;
        background: linear-gradient(135deg, var(--accent-1), #6341e0);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        position: relative;
        z-index: 1;
        overflow: hidden;
        transition: all var(--transition-fast);
        box-shadow: 0 4px 20px rgba(124, 92, 252, 0.3);
        margin-top: 8px;
      }

      .btn-login::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #6341e0, var(--accent-1));
        opacity: 0;
        transition: opacity var(--transition-fast);
        z-index: -1;
      }

      .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(124, 92, 252, 0.45);
      }

      .btn-login:hover::before {
        opacity: 1;
      }

      .btn-login:active {
        transform: translateY(0);
        transition-duration: 0.1s;
      }

      .error-message {
        text-align: center;
        padding: 12px 16px;
        margin-top: 20px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 500;
        color: var(--accent-2);
        background: rgba(240, 115, 110, 0.08);
        border: 1px solid rgba(240, 115, 110, 0.2);
        position: relative;
        z-index: 1;
        animation: shakeIn 0.4s ease;
      }

      @keyframes shakeIn {
        0%, 100% { transform: translateX(0); }
        20%      { transform: translateX(-6px); }
        40%      { transform: translateX(6px); }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(4px); }
      }

      .login-footer {
        text-align: center;
        margin-top: 28px;
        position: relative;
        z-index: 1;
      }

      .login-footer p {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 400;
      }

      .login-footer .dot-separator {
        display: inline-flex;
        gap: 4px;
        margin-top: 12px;
      }

      .login-footer .dot {
        width: 4px; height: 4px;
        border-radius: 50%;
        background: var(--text-muted);
        opacity: 0.5;
      }

      .login-footer .dot:nth-child(2) {
        background: var(--accent-1);
        opacity: 0.7;
      }

      ::-webkit-scrollbar { width: 6px; }
      ::-webkit-scrollbar-track { background: var(--bg-base); }
      ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
      ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.18); }

      ::selection {
        background: rgba(124, 92, 252, 0.3);
        color: #fff;
      }

      @media (max-width: 480px) {
        .login-card {
          padding: 36px 24px;
          border-radius: var(--radius-md);
        }

        .login-icon {
          width: 52px; height: 52px;
        }

        .login-header h1 { font-size: 1.4rem; }
      }
    </style>
</head>
<body>
    <div class="bg-orb"></div>

    <div class="login-container">
      <div class="login-card">

        <div class="login-header">
          <div class="login-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <h1>Connexion</h1>
          <p>Accédez à votre espace sécurisé</p>
        </div>

        <form method="POST" id="loginForm">
          <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <div class="input-wrapper">
              <input type="text" name="username" id="username" class="form-input" placeholder="Entrez votre nom" autocomplete="off" required>
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="input-wrapper">
              <input type="password" name="password" id="password" class="form-input" placeholder="Entrez votre mot de passe" required>
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </div>
          </div>

          <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <?php if (!empty($message)): ?>
          <div class="error-message">
            ⚠️ <?php echo $message; ?>
          </div>
        <?php endif; ?>

        <div class="login-footer">
          <div class="dot-separator">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
          </div>
        </div>

      </div>
    </div>
</body>
</html>