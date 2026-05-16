<?php
session_start();

// Vérifier que l'utilisateur est connecté et est client (role_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
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
    <title>Dashboard Client - SenLogis</title>
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
                        <p class="text-muted">Gérez vos commandes et suivez vos livraisons</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Commandes Totales</div>
                                <div class="stat-digit"><i class="fa fa-shopping-cart"></i> 12</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success w-80" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">En Transit</div>
                                <div class="stat-digit"><i class="fa fa-truck"></i> 5</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary w-60" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Livrées</div>
                                <div class="stat-digit"><i class="fa fa-check-circle"></i> 7</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">En Attente</div>
                                <div class="stat-digit"><i class="fa fa-hourglass-half"></i> 2</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger w-40" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Mes Commandes Récentes</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><strong>#Commande</strong></th>
                                            <th><strong>Date</strong></th>
                                            <th><strong>Destination</strong></th>
                                            <th><strong>Statut</strong></th>
                                            <th><strong>Montant</strong></th>
                                            <th><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CMD-001</td>
                                            <td>15/05/2026</td>
                                            <td>Dakar</td>
                                            <td><span class="badge badge-success">Livrée</span></td>
                                            <td>25,000 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                        <tr>
                                            <td>CMD-002</td>
                                            <td>14/05/2026</td>
                                            <td>Saint-Louis</td>
                                            <td><span class="badge badge-primary">En transit</span></td>
                                            <td>15,000 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                        <tr>
                                            <td>CMD-003</td>
                                            <td>13/05/2026</td>
                                            <td>Kaolack</td>
                                            <td><span class="badge badge-warning">En attente</span></td>
                                            <td>18,000 FCFA</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">Détails</a></td>
                                        </tr>
                                        <tr>
                                            <td>CMD-004</td>
                                            <td>12/05/2026</td>
                                            <td>Thiès</td>
                                            <td><span class="badge badge-success">Livrée</span></td>
                                            <td>22,000 FCFA</td>
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
                            <h4 class="card-title">Dépenses Mensuelles</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 border-right">
                                    <h5 class="mb-0">125,000 FCFA</h5>
                                    <small class="text-muted">Total Ce Mois</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">45,000 FCFA</h5>
                                    <small class="text-muted">Moyenne par Commande</small>
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
