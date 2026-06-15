<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 2) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/conteneurDB.php';
$conteneurs = getConteneursByProprietaire($_SESSION['user_id']);
?>
<?php require_once __DIR__ . '/views/pages/admin/head.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/preloader.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/nav-header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/sidebar.php'; ?>
<div class="content-body senlogis-dashboard owner-dashboard"><div class="container-fluid">
    <div class="row page-titles mx-0"><div class="col-sm-8 p-md-0"><div class="welcome-text"><h4>Mes conteneurs</h4><p class="text-muted">Liste des conteneurs rattaches a votre compte.</p></div></div></div>
    <?php if (isset($_SESSION['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
    <div class="card owner-section-card"><div class="card-header border-bottom d-flex justify-content-between align-items-center"><h4 class="card-title mb-1">Conteneurs</h4><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOwnerConteneurModal">Ajouter un conteneur</button></div><div class="card-body"><div class="row">
        <?php if (empty($conteneurs)): ?>
            <div class="col-12"><div class="owner-empty-state"><i class="fa fa-cube"></i><p>Aucun conteneur associe a votre compte.</p></div></div>
        <?php else: ?>
            <?php foreach ($conteneurs as $conteneur): ?>
                <div class="col-xl-4 col-md-6"><div class="owner-container-card"><img src="/SenLogis/public/img/container-card.svg" alt="Illustration conteneur"><div class="owner-container-content"><div class="owner-container-head"><div><span class="owner-card-label">Conteneur</span><h5><?php echo htmlspecialchars($conteneur['nom']); ?></h5></div><span class="owner-status status-<?php echo htmlspecialchars($conteneur['statut']); ?>"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $conteneur['statut']))); ?></span></div><div class="owner-card-meta"><div><i class="fa fa-map-marker"></i><span><?php echo htmlspecialchars($conteneur['position']); ?></span></div></div><div class="client-card-actions"><button type="button" class="btn btn-outline-primary btn-sm btn-edit-owner-conteneur" data-toggle="modal" data-target="#editOwnerConteneurModal" data-id="<?php echo htmlspecialchars($conteneur['id']); ?>" data-nom="<?php echo htmlspecialchars($conteneur['nom']); ?>" data-statut="<?php echo htmlspecialchars($conteneur['statut']); ?>" data-position="<?php echo htmlspecialchars($conteneur['position']); ?>">Modifier</button></div></div></div></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div></div></div>
</div></div>
<div class="modal fade" id="addOwnerConteneurModal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><form action="/SenLogis/controller/ownerConteneurController.php" method="POST">
    <div class="modal-header"><h5 class="modal-title">Ajouter un conteneur</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
    <div class="modal-body"><div class="form-group"><label>Nom</label><input type="text" name="nom" class="form-control" required></div><div class="form-group"><label>Statut initial</label><select name="statut" class="form-control" required><option value="disponible">Disponible</option><option value="maintenance">Maintenance</option></select></div><div class="form-group"><label>Position</label><input type="text" name="position" class="form-control" required></div></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button><button type="submit" name="btnAddOwnerConteneur" class="btn btn-primary">Enregistrer</button></div>
</form></div></div></div>
<div class="modal fade" id="editOwnerConteneurModal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><form action="/SenLogis/controller/ownerConteneurController.php" method="POST">
    <div class="modal-header"><h5 class="modal-title">Modifier un conteneur</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
    <div class="modal-body">
        <input type="hidden" name="id" id="edit_owner_conteneur_id">
        <div class="form-group"><label>Nom</label><input type="text" name="nom" id="edit_owner_conteneur_nom" class="form-control" required></div>
        <div class="form-group"><label>Statut</label><select name="statut" id="edit_owner_conteneur_statut" class="form-control" required><option value="disponible">Disponible</option><option value="maintenance">Maintenance</option></select><small class="form-text text-muted">Si le conteneur est reserve ou en livraison, le statut est conserve automatiquement.</small></div>
        <div class="form-group"><label>Position</label><input type="text" name="position" id="edit_owner_conteneur_position" class="form-control" required></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button><button type="submit" name="btnUpdateOwnerConteneur" class="btn btn-primary">Enregistrer</button></div>
</form></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-owner-conteneur').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_owner_conteneur_id').value = this.dataset.id;
            document.getElementById('edit_owner_conteneur_nom').value = this.dataset.nom;
            document.getElementById('edit_owner_conteneur_position').value = this.dataset.position;
            var statut = ['disponible', 'maintenance'].includes(this.dataset.statut) ? this.dataset.statut : 'disponible';
            document.getElementById('edit_owner_conteneur_statut').value = statut;
        });
    });
});
</script>
<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
