<?php
require_once __DIR__ . '/../../../model/userDB.php';
require_once __DIR__ . '/../../../model/siteStatsFile.php';

$totalUsers = countTableRows('users');
$totalConteneurs = countTableRows('conteneurs');
$totalLivraisons = countTableRows('livraisons');
$totalCommandes = countTableRows('commandes');
$totalPaiements = countTableRows('paiements');
$totalNotes = countTableRows('notes');
$totalVisitesVitrine = countVitrineVisits();
$totalVisiteursVitrine = countUniqueVitrineVisitors();
?>

<div class="content-body senlogis-dashboard">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-8 p-md-0">
                <div class="welcome-text">
                    <h4>Tableau de bord SenLogis</h4>
                    <p class="text-muted mb-0">Synthese de l'activite SenLogis et de la frequentation de la vitrine.</p>
                </div>
            </div>
            <div class="col-sm-4 p-md-0 text-sm-right mt-3 mt-sm-0">
                <span class="senlogis-badge">Pilotage admin</span>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Utilisateurs inscrits</p>
                            <h3><?php echo htmlspecialchars($totalUsers); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-users"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Conteneurs suivis</p>
                            <h3><?php echo htmlspecialchars($totalConteneurs); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-cube"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Commandes</p>
                            <h3><?php echo htmlspecialchars($totalCommandes); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-shopping-cart"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Livraisons</p>
                            <h3><?php echo htmlspecialchars($totalLivraisons); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-truck"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Paiements</p>
                            <h3><?php echo htmlspecialchars($totalPaiements); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-credit-card"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Notes clients</p>
                            <h3><?php echo htmlspecialchars($totalNotes); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-star"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Visites vitrine</p>
                            <h3><?php echo htmlspecialchars($totalVisitesVitrine); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-eye"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Visiteurs uniques</p>
                            <h3><?php echo htmlspecialchars($totalVisiteursVitrine); ?></h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-globe"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Modules de gestion</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a class="senlogis-link-card" href="/SenLogis/listeUsers">
                                    <i class="fa fa-users"></i>
                                    Utilisateurs et roles
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a class="senlogis-link-card" href="/SenLogis/listeConteneurs">
                                    <i class="fa fa-cube"></i>
                                    Conteneurs et positions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
