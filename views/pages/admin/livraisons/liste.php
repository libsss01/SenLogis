<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: /SenLogis/login.php');
    exit;
}

require_once __DIR__ . '/../../../../model/livraisonDB.php';
require_once __DIR__ . '/../../../../model/userDB.php';
require_once __DIR__ . '/../../../../model/conteneurDB.php';

$livraisons  = getAllLivraisons();
$users = getAllUsers();
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
                    <h4>Gestion des livraisons</h4>
                    <p class="text-muted">Suivi des adresses, dates, statuts et conteneurs associes.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Livraisons</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLivraisonModal">
                    Ajouter une livraison
                </button>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Adresse</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Client</th>
                                <th>Conteneur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($livraisons)): ?>
                
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Il existe pour le moment aucune livraison.</td>
                                </tr>
                            <?php else:?>
                                <?php foreach($livraisons as $livraison):?>
                                <tr>
                                        <td><?php echo htmlspecialchars($livraison['id']); ?></td>
                                        <td><?php echo htmlspecialchars($livraison['adresse']); ?></td>
                                        <td><?php echo htmlspecialchars($livraison['dateLivraison']); ?></td>
                                        <td><?php echo htmlspecialchars($livraison['statut']); ?></td>
                                        <td><?php echo htmlspecialchars($livraison['user_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($livraison['conteneur_nom']); ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning btn-edit-livraison"
                                                data-toggle="modal"
                                                data-target="#editLivraisonModal"
                                                data-id="<?php echo htmlspecialchars($livraison['id']); ?>"
                                                data-adresse="<?php echo htmlspecialchars($livraison['adresse']); ?>"
                                                data-date-livraison ="<?php echo htmlspecialchars($livraison['dateLivraison']); ?>"
                                                data-statut="<?php echo htmlspecialchars($livraison['statut']); ?>"
                                                data-user-nom="<?php echo htmlspecialchars($livraison['user_nom']); ?>"
                                                data-user-id="<?php echo htmlspecialchars($livraison['user_id']); ?>"
                                                data-conteneur-nom="<?php echo htmlspecialchars($livraison['conteneur_nom']); ?>"
                                                data-conteneur-id="<?php echo htmlspecialchars($livraison['conteneur_id']); ?>">
                                                Modifier
                                            </button>
                                            <button
                                            type="button"
                                            class="btn btn-sm btn-danger btn-delete-livraison"
                                            data-toggle="modal"
                                            data-target="#deleteLivraisonModal"
                                            data-id="<?php echo htmlspecialchars($livraison['id']); ?>"
                                            data-adresse="<?php echo htmlspecialchars($livraison['adresse']); ?>">
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

<div class="modal fade" id="addLivraisonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/livraisonController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une livraison</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" class="form-control" placeholder="Plateau, Dakar" required>
                    </div>
                    <div class="form-group">
                        <label>Date de livraison</label>
                        <input type="date" name="dateLivraison" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="validee">Validee</option>
                            <option value="en_cours">En cours</option>
                            <option value="livree">Livree</option>
                            <option value="annulee">Annulee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Choisir un utilisateur</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Conteneur</label>
                        <select name="conteneur_id" class="form-control" required>
                            <option value="">Choisir un conteneur</option>
                            <?php foreach ($conteneurs as $conteneur): ?>
                                <option value="<?php echo htmlspecialchars($conteneur['id']); ?>">
                                    <?php echo htmlspecialchars($conteneur['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnAddLivraison" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editLivraisonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/livraisonController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier une livraison</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_livraison_id">
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" id="edit_livraison_adresse" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Date de livraison</label>
                        <input type="date" name="dateLivraison" id="edit_livraison_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" id="edit_livraison_statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="validee">Validee</option>
                            <option value="en_cours">En cours</option>
                            <option value="livree">Livree</option>
                            <option value="annulee">Annulee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <select name="user_id" id="edit_livraison_user_id" class="form-control" required>
                            <option value="">Choisir un utilisateur</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Conteneur</label>
                        <select name="conteneur_id" id="edit_livraison_conteneur_id" class="form-control" required>
                            <option value="">Choisir un conteneur</option>
                            <?php foreach ($conteneurs as $conteneur): ?>
                                <option value="<?php echo htmlspecialchars($conteneur['id']); ?>">
                                    <?php echo htmlspecialchars($conteneur['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdateLivraison" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteLivraisonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/livraisonController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_livraison_id">
                    <p>Voulez-vous vraiment supprimer la livraison <strong id="delete_livraison_adresse"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnDeleteLivraison" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-livraison').forEach(function (button) {
        button.addEventListener('click', function () {
            
            document.getElementById('edit_livraison_id').value = this.dataset.id;
            document.getElementById('edit_livraison_adresse').value = this.dataset.adresse;
            document.getElementById('edit_livraison_date').value = this.dataset.dateLivraison;
            document.getElementById('edit_livraison_statut').value = this.dataset.statut;
            document.getElementById('edit_livraison_user_id').value = this.dataset.userId;
            document.getElementById('edit_livraison_conteneur_id').value = this.dataset.conteneurId;
        });
    });  

    document.querySelectorAll('.btn-delete-livraison').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('delete_livraison_id').value = this.dataset.id;
            document.getElementById('delete_livraison_adresse').textContent = this.dataset.adresse;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
