<?php 
session_start();
require_once 'controller/MainController.php';
processRegister();
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SenLogis - Inscription</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="public/template/templateAdmin/focus-2/images/favicon.png">
    <link href="public/template/templateAdmin/focus-2/css/style.css" rel="stylesheet">

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">S'enregistrer</h4>
                                    <?php if (isset($_SESSION['error'])): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <form action="register.php" method="POST">
                                        <div class="form-group">
                                            <label><strong>Nom Complet</strong></label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Mot de passe</strong></label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Confirmer le mot de passe</strong></label>
                                            <input type="password" name="confirm_password" class="form-control" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">S'enregistrer</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Déjà enregistré ? <a class="text-primary" href="login.php">Se connecter</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="public/template/templateAdmin/focus-2/vendor/global/global.min.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/quixnav-init.js"></script>
    <script src="public/template/templateAdmin/focus-2/js/custom.min.js"></script>

</body>

</html>
