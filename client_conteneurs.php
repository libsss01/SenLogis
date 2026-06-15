<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/conteneurDB.php';
$conteneursDisponibles = getConteneursDisponibles();
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
                    <h4>Conteneurs disponibles</h4>
                    <p class="text-muted">Choisissez un conteneur, indiquez votre adresse et envoyez une demande au proprietaire.</p>
                </div>
            </div>
        </div>
        <?php if (isset($_SESSION['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div><?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>

        <div class="row">
            <?php if (empty($conteneursDisponibles)): ?>
                <div class="col-12"><div class="owner-empty-state"><i class="fa fa-cube"></i><p>Aucun conteneur disponible pour le moment.</p></div></div>
            <?php else: ?>
                <?php foreach ($conteneursDisponibles as $conteneur): ?>
                    <div class="col-xl-4 col-md-6">
                        <div class="owner-container-card client-container-card">
                            <img src="/SenLogis/public/img/container-card.svg" alt="Illustration conteneur">
                            <div class="owner-container-content">
                                <div class="owner-container-head">
                                    <div>
                                        <span class="owner-card-label">Conteneur disponible</span>
                                        <h5><?php echo htmlspecialchars($conteneur['nom']); ?></h5>
                                    </div>
                                    <span class="owner-status status-disponible">Disponible</span>
                                </div>
                                <div class="owner-card-meta"><div><i class="fa fa-map-marker"></i><span><?php echo htmlspecialchars($conteneur['position']); ?></span></div><div><i class="fa fa-user"></i><span><?php echo htmlspecialchars($conteneur['proprietaire_prenom'] . ' ' . $conteneur['proprietaire_nom']); ?></span></div></div>
                                <div class="client-card-actions">
                                    <button type="button" class="btn btn-primary btn-sm btn-commander-conteneur" data-toggle="modal" data-target="#commandeConteneurModal" data-id="<?php echo htmlspecialchars($conteneur['id']); ?>" data-nom="<?php echo htmlspecialchars($conteneur['nom']); ?>">Commander</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="commandeConteneurModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document"><div class="modal-content"><form action="/SenLogis/controller/clientRequestController.php" method="POST">
        <div class="modal-header"><h5 class="modal-title">Commander un conteneur</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <input type="hidden" name="conteneur_id" id="commande_conteneur_id">
            <p>Conteneur choisi: <strong id="commande_conteneur_nom"></strong></p>
            <div class="form-group"><label>Adresse de livraison</label><input type="text" name="adresse" class="form-control" required></div>
            <div class="form-group"><label>Date souhaitee</label><input type="date" name="dateLivraison" class="form-control" required></div>
            <div class="form-group"><label>Methode</label><select name="methode" class="form-control" required><option value="en_ligne">En ligne</option><option value="agence">Agence</option><option value="telephone">Telephone</option></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button><button type="submit" name="btnCreateClientRequest" class="btn btn-primary">Envoyer</button></div>
    </form></div></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-commander-conteneur').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('commande_conteneur_id').value = this.dataset.id;
            document.getElementById('commande_conteneur_nom').textContent = this.dataset.nom;
        });
    });
});
</script>

<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
