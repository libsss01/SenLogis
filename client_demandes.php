<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/commandeDB.php';
require_once __DIR__ . '/model/noteDB.php';

$commandes = getCommandesByUser($_SESSION['user_id']);
$notes = getNotesByUser($_SESSION['user_id']);
$notesByLivraison = [];
foreach ($notes as $note) {
    $notesByLivraison[$note['numLivraison']] = $note;
}

$livraisonsArrivees = array_filter($commandes, function ($commande) {
    return $commande['livraison_statut'] === 'livree';
});
?>
<?php require_once __DIR__ . '/views/pages/admin/head.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/preloader.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/nav-header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/sidebar.php'; ?>

<div class="content-body senlogis-dashboard owner-dashboard client-dashboard">
    <div class="container-fluid">
        <div class="row page-titles mx-0"><div class="col-sm-8 p-md-0"><div class="welcome-text"><h4>Mes demandes</h4><p class="text-muted">Suivi de vos commandes et livraisons.</p></div></div></div>
        <?php if (isset($_SESSION['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
        <?php if (!empty($livraisonsArrivees)): ?>
            <div class="alert alert-info">
                <strong>Livraison arrivee.</strong> Confirmez la reception pour finaliser la livraison et pouvoir la noter.
            </div>
        <?php endif; ?>
        <div class="card owner-section-card"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
            <thead><tr><th>#</th><th>Adresse</th><th>Date livraison</th><th>Conteneur</th><th>Commande</th><th>Livraison</th><th>Reception</th><th>Note</th></tr></thead>
            <tbody>
            <?php if (empty($commandes)): ?>
                <tr><td colspan="8" class="text-center text-muted">Aucune demande pour le moment.</td></tr>
            <?php else: ?>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($commande['id']); ?></td>
                        <td><?php echo htmlspecialchars($commande['adresse']); ?></td>
                        <td><?php echo htmlspecialchars($commande['dateLivraison']); ?></td>
                        <td><?php echo htmlspecialchars($commande['conteneur_nom']); ?></td>
                        <td><span class="badge badge-info"><?php echo htmlspecialchars($commande['statut']); ?></span></td>
                        <td><span class="badge badge-primary"><?php echo htmlspecialchars($commande['livraison_statut']); ?></span></td>
                        <td>
                            <?php if ($commande['livraison_statut'] === 'livree'): ?>
                                <form action="/SenLogis/controller/clientDeliveryController.php" method="POST" class="m-0">
                                    <input type="hidden" name="livraison_id" value="<?php echo htmlspecialchars($commande['livraison_id']); ?>">
                                    <button type="submit" name="btnConfirmClientDelivery" class="btn btn-sm btn-success">Confirmer</button>
                                </form>
                            <?php elseif ($commande['livraison_statut'] === 'confirmee'): ?>
                                <span class="badge badge-success">Confirmee</span>
                            <?php else: ?>
                                <span class="text-muted">En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($notesByLivraison[$commande['livraison_id']])): ?>
                                <span class="badge badge-success"><?php echo htmlspecialchars($notesByLivraison[$commande['livraison_id']]['note']); ?>/5</span>
                            <?php elseif ($commande['livraison_statut'] === 'confirmee'): ?>
                                <button type="button" class="btn btn-sm btn-primary btn-note-livraison" data-toggle="modal" data-target="#noteLivraisonModal" data-livraison-id="<?php echo htmlspecialchars($commande['livraison_id']); ?>" data-conteneur="<?php echo htmlspecialchars($commande['conteneur_nom']); ?>">Noter</button>
                            <?php else: ?>
                                <span class="text-muted">Apres confirmation</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table></div></div></div>
    </div>
</div>

<div class="modal fade" id="noteLivraisonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document"><div class="modal-content"><form action="/SenLogis/controller/clientNoteController.php" method="POST">
        <div class="modal-header"><h5 class="modal-title">Noter une livraison</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body"><input type="hidden" name="numLivraison" id="note_livraison_id"><p>Livraison du conteneur: <strong id="note_livraison_conteneur"></strong></p><div class="form-group"><label>Votre note</label><select name="note" class="form-control" required><option value="">Choisir</option><option value="5">5 - Excellent</option><option value="4">4 - Tres bien</option><option value="3">3 - Correct</option><option value="2">2 - Moyen</option><option value="1">1 - Insatisfait</option></select></div></div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button><button type="submit" name="btnAddClientNote" class="btn btn-primary">Enregistrer</button></div>
    </form></div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-note-livraison').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('note_livraison_id').value = this.dataset.livraisonId;
            document.getElementById('note_livraison_conteneur').textContent = this.dataset.conteneur;
        });
    });
});
</script>
<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
