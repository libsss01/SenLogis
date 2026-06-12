<?php
session_start();
$old = $_SESSION['old'] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SenLogis - Inscription</title>
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

                <h1 class="auth-title">Creer un compte</h1>
                <p class="auth-subtitle">Creez un acces client pour suivre vos commandes et livraisons.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="controller/authController.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prenom</label>
                                <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($old['prenom'] ?? ''); ?>" autocomplete="given-name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($old['nom'] ?? ''); ?>" autocomplete="family-name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" autocomplete="email" required>
                    </div>
                    <div class="form-group">
                        <label>Telephone</label>
                        <input type="tel" name="telephone" class="form-control" value="<?php echo htmlspecialchars($old['telephone'] ?? ''); ?>" placeholder="+221 77 000 00 00" autocomplete="tel">
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="confirm_password" class="form-control" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="btnRegister">S'enregistrer</button>
                </form>

                <p class="auth-switch">Deja enregistre ? <a href="login.php">Se connecter</a></p>
            </div>
        </section>

        <section class="auth-visual" aria-label="Port et conteneurs">
            <div class="auth-visual-content">
                <span class="auth-visual-kicker">SenLogis Senegal</span>
                <h2>Un acces simple pour vos operations.</h2>
            </div>
        </section>
    </main>

    <script src="public/template/templateAdmin/focus-2/vendor/global/global.min.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/quixnav-init.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/custom.min.js"></script>

<?php unset($_SESSION['old']); ?>
</body>

</html>
