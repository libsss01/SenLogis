<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}
require_once __DIR__ . '/../../../../model/conteneurDB.php';
$conteneurs = getAllConteneurs();
?>
<?php require_once __DIR__ . '/../head.php'; ?>
<?php require_once __DIR__ . '/../preloader.php'; ?>
<?php require_once __DIR__ . '/../nav-header.php'; ?>
<?php require_once __DIR__ . '/../header.php'; ?>
<?php require_once __DIR__ . '/../sidebar.php'; ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Gestion des conteneurs</h4>
                    <p class="text-muted">Liste, ajout, modification et suppression des conteneurs.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Conteneurs</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addConteneurModal">
                    Ajouter un conteneur
                </button>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($conteneurs)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Aucun conteneur enregistré.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($conteneurs as $conteneur): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($conteneur['id']); ?></td>
                                    <td><?php echo htmlspecialchars($conteneur['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($conteneur['statut']); ?></td>
                                    <td><?php echo htmlspecialchars($conteneur['position']); ?></td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning btn-edit-conteneur"
                                            data-toggle="modal"
                                            data-target="#editConteneurModal"
                                            data-id="<?php echo htmlspecialchars($conteneur['id']); ?>"
                                            data-nom="<?php echo htmlspecialchars($conteneur['nom']); ?>"
                                            data-statut="<?php echo htmlspecialchars($conteneur['statut']); ?>"
                                            data-position="<?php echo htmlspecialchars($conteneur['position']); ?>">
                                            Modifier
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger btn-delete-conteneur"
                                            data-toggle="modal"
                                            data-target="#deleteConteneurModal"
                                            data-id="<?php echo htmlspecialchars($conteneur['id']); ?>"
                                            data-nom="<?php echo htmlspecialchars($conteneur['nom']); ?>">
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajout conteneur -->
<div class="modal fade" id="addConteneurModal" tabindex="-1" role="dialog" aria-labelledby="addConteneurModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/conteneurController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addConteneurModalLabel">Ajouter un conteneur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom du conteneur</label>
                        <input type="text" name="nom" class="form-control" placeholder="CONT-DKR-001" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="disponible">Disponible</option>
                            <option value="reserve">Reserve</option>
                            <option value="en_livraison">En livraison</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" class="form-control" placeholder="Port autonome de Dakar" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnAddConteneur" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal modification conteneur -->
<div class="modal fade" id="editConteneurModal" tabindex="-1" role="dialog" aria-labelledby="editConteneurModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/conteneurController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editConteneurModalLabel">Modifier un conteneur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_conteneur_id">
                    <div class="form-group">
                        <label>Nom du conteneur</label>
                        <input type="text" name="nom" id="edit_conteneur_nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" id="edit_conteneur_statut" class="form-control" required>
                            <option value="disponible">Disponible</option>
                            <option value="reserve">Reserve</option>
                            <option value="en_livraison">En livraison</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" id="edit_conteneur_position" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdateConteneur" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal suppression conteneur -->
<div class="modal fade" id="deleteConteneurModal" tabindex="-1" role="dialog" aria-labelledby="deleteConteneurModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/conteneurController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConteneurModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_conteneur_id">
                    <p>Voulez-vous vraiment supprimer le conteneur <strong id="delete_conteneur_nom"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnDeleteConteneur" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-conteneur').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_conteneur_id').value = this.dataset.id;
            document.getElementById('edit_conteneur_nom').value = this.dataset.nom;
            document.getElementById('edit_conteneur_statut').value = this.dataset.statut;
            document.getElementById('edit_conteneur_position').value = this.dataset.position;
        });
    });

    document.querySelectorAll('.btn-delete-conteneur').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('delete_conteneur_id').value = this.dataset.id;
            document.getElementById('delete_conteneur_nom').textContent = this.dataset.nom;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
