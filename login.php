<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SenLogis - Connexion</title>
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

                <h1 class="auth-title">Se connecter</h1>
                <p class="auth-subtitle">Accedez a votre espace de gestion des conteneurs.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="controller/authController.php" method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" autocomplete="email" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" autocomplete="current-password" required>
                    </div>
                    <div class="auth-meta">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="#">Mot de passe oublie ?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="btnLogin">Se connecter</button>
                </form>

                <p class="auth-switch">Pas encore de compte ? <a href="register.php">Creer un compte</a></p>
            </div>
        </section>

        <section class="auth-visual" aria-label="Port et conteneurs">
            <div class="auth-visual-content">
                <span class="auth-visual-kicker">Port autonome de Dakar</span>
                <h2>Gestion des conteneurs au Senegal.</h2>
            </div>
        </section>
    </main>

    <script src="public/template/templateAdmin/focus-2/vendor/global/global.min.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/quixnav-init.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/custom.min.js"></script>
</body>

</html>
