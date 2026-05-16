<?php
session_start();

// Vérifier que l'utilisateur est connecté et est propriétaire (role_id = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 2) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Propriétaire - SenLogis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="public/template/templateAdmin/focus-2/images/favicon.png">
    <link rel="stylesheet" href="public/template/templateAdmin/focus-2/vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="public/template/templateAdmin/focus-2/vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="public/template/templateAdmin/focus-2/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/icons/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/icons/avasta/css/style.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/icons/font-awesome-old/css/font-awesome.min.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/icons/material-design-iconic-font/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/icons/themify-icons/css/themify-icons.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/icons/line-awesome/css/line-awesome.min.css" rel="stylesheet">
    <link href="public/template/templateAdmin/focus-2/css/style.css" rel="stylesheet">

</head>

<body>

    <!-- =========================== PRELOADER ====================-->
    <?php require_once("views/pages/admin/preloader.php"); ?>

    <!-- =========================== NAV HEADER ====================-->
    <?php require_once("views/pages/admin/nav-header.php"); ?>

    <!-- =========================== HEADER ====================-->
    <?php require_once("views/pages/admin/header.php"); ?>

    <!-- =========================== SIDEBAR ====================-->
    <?php require_once("views/pages/admin/sidebar.php"); ?>

    <!-- =========================== CONTENT ====================-->
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h4>
                        <p class="text-muted">Gérez vos conteneurs et vos locations</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Conteneurs Total</div>
                                <div class="stat-digit"><i class="fa fa-cube"></i> 8</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Locations Actives</div>
                                <div class="stat-digit"><i class="fa fa-calendar"></i> 6</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Revenus Mensuels</div>
                                <div class="stat-digit"><i class="fa fa-money"></i> 2.5M</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning w-90" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Taux Utilisation</div>
                                <div class="stat-digit"><i class="fa fa-percent"></i> 87%</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger w-87" role="progressbar" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mes Conteneurs Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Mes Conteneurs</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><strong>N° Conteneur</strong></th>
                                            <th><strong>Type</strong></th>
                                            <th><strong>Localisation</strong></th>
                                            <th><strong>Statut</strong></th>
                                            <th><strong>Location Actuelle</strong></th>
                                            <th><strong>Revenu Mensuel</strong></th>
                                            <th><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CONT-001</td>
                                            <td>20 pieds</td>
                                            <td>Port de Dakar</td>
                                            <td><span class="badge badge-success">Actif</span></td>
                                            <td>ExpressLog SA</td>
                                            <td>350,000 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                        <tr>
                                            <td>CONT-002</td>
                                            <td>40 pieds</td>
                                            <td>Port de Dakar</td>
                                            <td><span class="badge badge-success">Actif</span></td>
                                            <td>GlobalShip Inc</td>
                                            <td>450,000 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                        <tr>
                                            <td>CONT-003</td>
                                            <td>20 pieds</td>
                                            <td>Thiès</td>
                                            <td><span class="badge badge-success">Actif</span></td>
                                            <td>TradeFlow Ltd</td>
                                            <td>300,000 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                        <tr>
                                            <td>CONT-004</td>
                                            <td>40 pieds</td>
                                            <td>Saint-Louis</td>
                                            <td><span class="badge badge-warning">Maintenance</span></td>
                                            <td>-</td>
                                            <td>0 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Revenus par Mois (6 derniers mois)</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 border-right">
                                    <h5 class="mb-0">13.5M FCFA</h5>
                                    <small class="text-muted">Total 6 Mois</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">2.25M FCFA</h5>
                                    <small class="text-muted">Moyenne Mensuelle</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Mon Profil</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Nom:</strong></p>
                                    <p class="text-muted"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Email:</strong></p>
                                    <p class="text-muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                                </div>
                            </div>
                            <p><strong>Rôle:</strong></p>
                            <p><span class="badge badge-info">Propriétaire Conteneur</span></p>
                            <a href="user_profile.php" class="btn btn-sm btn-primary mt-2">Voir Profil Complet</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================== FOOTER ====================-->
    <?php require_once("views/pages/admin/footer.php"); ?>

    <!-- =========================== SCRIPTS ====================-->
    <?php require_once("views/pages/admin/scripts.php"); ?>

</body>

</html>
