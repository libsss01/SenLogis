<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 2) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/livraisonDB.php';
$demandes = getDemandesLivraisonByProprietaire($_SESSION['user_id']);
?>
<?php require_once __DIR__ . '/views/pages/admin/head.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/preloader.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/nav-header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/sidebar.php'; ?>
<div class="content-body senlogis-dashboard owner-dashboard"><div class="container-fluid">
    <div class="row page-titles mx-0"><div class="col-sm-8 p-md-0"><div class="welcome-text"><h4>Demandes recues</h4><p class="text-muted">Suivi des demandes envoyees sur vos conteneurs.</p></div></div></div>
    <?php if (isset($_SESSION['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card owner-section-card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Client</th><th>Conteneur</th><th>Adresse</th><th>Date souhaitee</th><th>Methode</th><th>Statut</th><th>Actions</th></tr></thead><tbody>
    <?php if (empty($demandes)): ?><tr><td colspan="7" class="text-center text-muted">Aucune demande pour le moment.</td></tr><?php else: ?>
        <?php foreach ($demandes as $demande): ?><tr><td><?php echo htmlspecialchars($demande['client_prenom'] . ' ' . $demande['client_nom']); ?></td><td><?php echo htmlspecialchars($demande['conteneur_nom']); ?></td><td><?php echo htmlspecialchars($demande['adresse']); ?></td><td><?php echo htmlspecialchars($demande['dateLivraison']); ?></td><td><?php echo htmlspecialchars($demande['commande_methode']); ?></td><td><span class="badge badge-primary"><?php echo htmlspecialchars($demande['statut']); ?></span></td><td><form action="/SenLogis/controller/ownerRequestController.php" method="POST" class="owner-request-actions"><input type="hidden" name="livraison_id" value="<?php echo htmlspecialchars($demande['id']); ?>"><?php if ($demande['statut'] === 'en_attente'): ?><button type="submit" name="btnAcceptOwnerRequest" class="btn btn-sm btn-success">Accepter</button><button type="submit" name="btnRejectOwnerRequest" class="btn btn-sm btn-outline-danger">Refuser</button><?php elseif (in_array($demande['statut'], ['validee', 'en_cours'], true)): ?><button type="submit" name="btnMarkDeliveredOwnerRequest" class="btn btn-sm btn-primary">Marquer arrivee</button><?php elseif ($demande['statut'] === 'livree'): ?><span class="text-muted">En attente confirmation client</span><?php elseif ($demande['statut'] === 'confirmee'): ?><span class="badge badge-success">Reception confirmee</span><?php else: ?><span class="text-muted">Aucune action</span><?php endif; ?></form></td></tr><?php endforeach; ?>
    <?php endif; ?>
    </tbody></table></div></div></div>
</div></div>
<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
