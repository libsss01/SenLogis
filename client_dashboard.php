<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/conteneurDB.php';
require_once __DIR__ . '/model/commandeDB.php';
require_once __DIR__ . '/model/livraisonDB.php';
require_once __DIR__ . '/model/paiementDB.php';

$conteneursDisponibles = getConteneursDisponibles();
$commandes = getCommandesByUser($_SESSION['user_id']);
$livraisons = getLivraisonsByUser($_SESSION['user_id']);
$paiements = getPaiementsByUser($_SESSION['user_id']);
?>
<?php require_once __DIR__ . '/views/pages/admin/head.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/preloader.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/nav-header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/sidebar.php'; ?>

<div class="content-body senlogis-dashboard owner-dashboard client-dashboard">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-8 p-md-0">
                <div class="welcome-text">
                    <h4>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                    <p class="text-muted">Votre espace client SenLogis en un coup d'oeil.</p>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="row owner-stat-row">
            <div class="col-lg-3 col-sm-6">
                <div class="card senlogis-owner-stat"><div class="card-body"><div class="owner-stat-icon"><i class="fa fa-cube"></i></div><div><span>Conteneurs disponibles</span><strong><?php echo count($conteneursDisponibles); ?></strong></div></div></div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card senlogis-owner-stat"><div class="card-body"><div class="owner-stat-icon"><i class="fa fa-shopping-cart"></i></div><div><span>Mes commandes</span><strong><?php echo count($commandes); ?></strong></div></div></div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card senlogis-owner-stat"><div class="card-body"><div class="owner-stat-icon"><i class="fa fa-truck"></i></div><div><span>Mes livraisons</span><strong><?php echo count($livraisons); ?></strong></div></div></div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card senlogis-owner-stat"><div class="card-body"><div class="owner-stat-icon"><i class="fa fa-credit-card"></i></div><div><span>Mes paiements</span><strong><?php echo count($paiements); ?></strong></div></div></div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card owner-section-card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title mb-1">Raccourcis</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 mb-3"><a class="senlogis-link-card" href="/SenLogis/clientConteneurs"><i class="fa fa-cube"></i> Conteneurs disponibles</a></div>
                        <div class="col-md-4 mb-3"><a class="senlogis-link-card" href="/SenLogis/clientDemandes"><i class="fa fa-truck"></i> Mes demandes</a></div>
                         <div class="col-md-4 mb-3"><a class="senlogis-link-card" href="/SenLogis/clientPaiements"><i class="fa fa-credit-card"></i> Mes paiements</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
