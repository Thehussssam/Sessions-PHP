<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];

$roleBadges = [
    "administrateur" => ["icon" => "⚙️", "color" => "#7c5cfc"],
    "formateur"      => ["icon" => "📚", "color" => "#3eddc6"],
    "apprenant"      => ["icon" => "🎓", "color" => "#f0736e"],
];

$roleInfo = $roleBadges[$user["role"]] ?? ["icon" => "👤", "color" => "#8888a4"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — <?php echo htmlspecialchars($user["name"]); ?></title>
    <meta name="description" content="Tableau de bord utilisateur — gérez votre espace sécurisé.">
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
        overflow-x: hidden;
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
        background: radial-gradient(circle, rgba(124, 92, 252, 0.18), transparent 70%);
        top: -200px; left: -150px;
        animation: orbFloat1 18s ease-in-out infinite alternate;
      }

      body::after {
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(240, 115, 110, 0.12), transparent 70%);
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
        background: radial-gradient(circle, rgba(62, 221, 198, 0.1), transparent 70%);
        top: 40%; left: 55%;
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

      .page-wrapper {
        position: relative;
        z-index: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 32px;
        border-bottom: 1px solid var(--border-subtle);
        background: rgba(8, 8, 15, 0.6);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        position: sticky;
        top: 0;
        z-index: 10;
        animation: fadeDown 0.6s ease both;
      }

      @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
      }

      .navbar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .navbar-logo {
        width: 36px; height: 36px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(124, 92, 252, 0.25);
      }

      .navbar-logo svg {
        width: 18px; height: 18px;
        color: #fff;
      }

      .navbar-title {
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: -0.3px;
        background: linear-gradient(135deg, #fff 40%, var(--accent-3));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .btn-logout {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        background: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-full);
        text-decoration: none;
        transition: all var(--transition-fast);
        cursor: pointer;
      }

      .btn-logout svg {
        width: 16px; height: 16px;
        transition: transform var(--transition-fast);
      }

      .btn-logout:hover {
        color: var(--accent-2);
        background: rgba(240, 115, 110, 0.08);
        border-color: rgba(240, 115, 110, 0.25);
        transform: translateY(-1px);
      }

      .btn-logout:hover svg {
        transform: translateX(3px);
      }

      .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 48px 24px 80px;
        max-width: 1000px;
        width: 100%;
        margin: 0 auto;
      }

      .welcome-section {
        text-align: center;
        margin-bottom: 48px;
        animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
      }

      @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
      }

      .avatar {
        width: 88px; height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-1), var(--accent-3));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 2rem;
        box-shadow: 0 8px 40px rgba(124, 92, 252, 0.25);
        position: relative;
        animation: avatarPulse 4s ease-in-out infinite;
      }

      @keyframes avatarPulse {
        0%, 100% { box-shadow: 0 8px 40px rgba(124, 92, 252, 0.25); }
        50%      { box-shadow: 0 8px 60px rgba(124, 92, 252, 0.4); }
      }

      .avatar-letter {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
      }

      .welcome-section h1 {
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        font-weight: 700;
        background: linear-gradient(135deg, #fff 30%, var(--accent-3) 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
      }

      .welcome-section .subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 300;
      }

      .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 16px;
        padding: 6px 18px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        background: rgba(124, 92, 252, 0.1);
        border: 1px solid rgba(124, 92, 252, 0.2);
        color: var(--accent-1);
        animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both;
      }

      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        width: 100%;
        margin-bottom: 40px;
      }

      .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 28px 24px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
        transition: all var(--transition-smooth);
      }

      .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--accent-1), var(--accent-2), var(--accent-3));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
      }

      .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--border-accent);
        box-shadow: var(--shadow-card);
      }

      .stat-card:hover::before {
        transform: scaleX(1);
      }

      .stat-card:nth-child(1) { animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
      .stat-card:nth-child(2) { animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both; }
      .stat-card:nth-child(3) { animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both; }

      .stat-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 16px;
      }

      .stat-icon.purple { background: rgba(124, 92, 252, 0.12); }
      .stat-icon.teal   { background: rgba(62, 221, 198, 0.12); }
      .stat-icon.coral   { background: rgba(240, 115, 110, 0.12); }

      .stat-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
      }

      .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
      }

      .info-panel {
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-lg);
        padding: 32px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
        animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both;
      }

      .info-panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-3), var(--accent-1));
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
      }

      .info-panel h2 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .info-panel h2 svg {
        width: 20px; height: 20px;
        color: var(--accent-3);
      }

      .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid var(--border-subtle);
      }

      .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
      }

      .info-row .label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .info-row .label svg {
        width: 16px; height: 16px;
        opacity: 0.5;
      }

      .info-row .value {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
      }

      .status-active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--accent-3);
        font-weight: 600;
        font-size: 13px;
      }

      .status-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--accent-3);
        animation: dotPulse 2s ease-in-out infinite;
      }

      @keyframes dotPulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(62, 221, 198, 0.4); }
        50%      { opacity: 0.7; box-shadow: 0 0 0 6px rgba(62, 221, 198, 0); }
      }

      footer {
        text-align: center;
        padding: 24px;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 400;
        border-top: 1px solid var(--border-subtle);
        letter-spacing: 0.2px;
      }

      ::-webkit-scrollbar { width: 6px; }
      ::-webkit-scrollbar-track { background: var(--bg-base); }
      ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
      ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.18); }

      ::selection {
        background: rgba(124, 92, 252, 0.3);
        color: #fff;
      }

      @media (max-width: 640px) {
        .navbar { padding: 14px 20px; }
        .navbar-title { display: none; }

        .main-content { padding: 32px 16px 60px; }

        .avatar { width: 72px; height: 72px; }
        .avatar-letter { font-size: 1.6rem; }

        .stats-grid {
          grid-template-columns: 1fr;
        }

        .info-panel { padding: 24px 20px; }
        .info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
      }
    </style>
</head>
<body>
    <!-- Floating background orb -->
    <div class="bg-orb"></div>

    <div class="page-wrapper">

      <!-- ═══ NAVBAR ═══ -->
      <nav class="navbar" id="navbar">
        <div class="navbar-brand">
          <div class="navbar-logo">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </div>
          <span class="navbar-title">Dashboard</span>
        </div>

        <a href="logout.php" class="btn-logout" id="logoutBtn">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Se déconnecter
        </a>
      </nav>

      <!-- ═══ MAIN CONTENT ═══ -->
      <main class="main-content">

        <!-- Welcome Section -->
        <section class="welcome-section" id="welcomeSection">
          <div class="avatar">
            <span class="avatar-letter"><?php echo strtoupper(substr($user["name"], 0, 1)); ?></span>
          </div>
          <h1>Bienvenue, <?php echo htmlspecialchars($user["name"]); ?> 👋</h1>
          <p class="subtitle">Votre espace personnel est prêt</p>
          <div class="role-badge" style="background: <?php echo $roleInfo['color']; ?>15; border-color: <?php echo $roleInfo['color']; ?>30; color: <?php echo $roleInfo['color']; ?>;">
            <?php echo $roleInfo['icon']; ?> <?php echo ucfirst($user["role"]); ?>
          </div>
        </section>

        <!-- Stats Cards -->
        <div class="stats-grid" id="statsGrid">
          <div class="stat-card">
            <div class="stat-icon purple">👤</div>
            <div class="stat-label">Utilisateur</div>
            <div class="stat-value"><?php echo htmlspecialchars($user["name"]); ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon teal">🛡️</div>
            <div class="stat-label">Rôle</div>
            <div class="stat-value"><?php echo ucfirst($user["role"]); ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon coral">📊</div>
            <div class="stat-label">Statut</div>
            <div class="stat-value">
              <span class="status-active">
                <span class="status-dot"></span>
                Actif
              </span>
            </div>
          </div>
        </div>

        <!-- Info Panel -->
        <div class="info-panel" id="infoPanel">
          <h2>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="16" x2="12" y2="12"/>
              <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            Informations de session
          </h2>

          <div class="info-row">
            <span class="label">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              Nom d'utilisateur
            </span>
            <span class="value"><?php echo htmlspecialchars($user["name"]); ?></span>
          </div>

          <div class="info-row">
            <span class="label">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
              Rôle assigné
            </span>
            <span class="value"><?php echo ucfirst($user["role"]); ?></span>
          </div>

          <div class="info-row">
            <span class="label">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
              </svg>
              Connexion
            </span>
            <span class="value" id="loginTime"></span>
          </div>

          <div class="info-row">
            <span class="label">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
              </svg>
              Statut du compte
            </span>
            <span class="value">
              <span class="status-active">
                <span class="status-dot"></span>
                Actif
              </span>
            </span>
          </div>
        </div>

      </main>

    </div>

    <script>
      // Display current login time
      const now = new Date();
      const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
      document.getElementById('loginTime').textContent = now.toLocaleDateString('fr-FR', options);
    </script>
</body>
</html>