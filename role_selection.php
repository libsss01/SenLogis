<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$currentRoleId = $_SESSION['user_role_id'] ?? null;

if ($currentRoleId !== null) {
    $dashboard = [
        2 => 'dashboardProprietaire',
        1 => 'clientDashboard'
    ];

    header('Location: ' . ($dashboard[$currentRoleId] ?? 'clientDashboard'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SenLogis - Choisir le type de compte</title>
    <link rel="icon" type="image/png" sizes="16x16" href="public/template/templateAdmin/focus-2/images/favicon.png">
    <link href="public/template/templateAdmin/focus-2/css/style.css" rel="stylesheet">
    <link href="public/css/auth.css" rel="stylesheet">
</head>

<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-form-panel">
            <div class="auth-card">
                <a href="index.php" class="auth-brand">
                    <span class="auth-brand-mark">SL</span>
                    <span>SenLogis</span>
                </a>

                <h1 class="auth-title">Choisir votre espace</h1>
                <p class="auth-subtitle">Ce choix determine les pages auxquelles votre compte aura acces.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="controller/roleController.php" method="POST">
                    <div class="form-group">
                        <label>Type de compte</label>
                        <select name="role_id" class="form-control" required>
                            <option value="1">Client - suivre mes demandes et paiements</option>
                            <option value="2">Proprietaire - gerer mes conteneurs</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" name="btnChooseRole">Continuer</button>
                </form>

                <p class="auth-switch"><a href="controller/authController.php?action=logout">Se deconnecter</a></p>
            </div>
        </section>

        <section class="auth-visual" aria-label="Choix de compte SenLogis">
            <div class="auth-visual-content">
                <span class="auth-visual-kicker">SenLogis Senegal</span>
                <h2>Un espace adapte a votre role.</h2>
            </div>
        </section>
    </main>

    <script src="public/template/templateAdmin/focus-2/vendor/global/global.min.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/quixnav-init.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/custom.min.js"></script>
</body>

</html>
