<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/paiementDB.php';
$paiements = getPaiementsByUser($_SESSION['user_id']);
?>
<?php require_once __DIR__ . '/views/pages/admin/head.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/preloader.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/nav-header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/sidebar.php'; ?>
<div class="content-body senlogis-dashboard owner-dashboard client-dashboard"><div class="container-fluid">
    <div class="row page-titles mx-0"><div class="col-sm-8 p-md-0"><div class="welcome-text"><h4>Mes paiements</h4><p class="text-muted">Historique des paiements lies a vos commandes.</p></div></div></div>
    <div class="card owner-section-card"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
        <thead><tr><th>Reference</th><th>Commande</th><th>Conteneur</th><th>Montant</th><th>Methode</th><th>Statut</th></tr></thead>
        <tbody>
        <?php if (empty($paiements)): ?>
            <tr><td colspan="6" class="text-center text-muted">Aucun paiement enregistre.</td></tr>
        <?php else: ?>
            <?php foreach ($paiements as $paiement): ?>
                <tr><td><?php echo htmlspecialchars($paiement['reference']); ?></td><td>#<?php echo htmlspecialchars($paiement['commande_id']); ?></td><td><?php echo htmlspecialchars($paiement['conteneur_nom']); ?></td><td><?php echo htmlspecialchars(number_format($paiement['montant'], 0, ',', ' ')); ?> FCFA</td><td><?php echo htmlspecialchars($paiement['methode']); ?></td><td><span class="badge badge-info"><?php echo htmlspecialchars($paiement['statut']); ?></span></td></tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div></div></div>
</div></div>
<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
