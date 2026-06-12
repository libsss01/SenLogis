<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}
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
                    <h4>Gestion des commandes</h4>
                    <p class="text-muted">Commandes clients, statuts et methode de commande.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Commandes</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCommandeModal">
                    Ajouter une commande
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
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Methode</th>
                                <th>Client</th>
                                <th>Livraison</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Les commandes seront affichees ici apres branchement du model.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCommandeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/commandeController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une commande</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="confirmee">Confirmee</option>
                            <option value="payee">Payee</option>
                            <option value="annulee">Annulee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Methode</label>
                        <select name="methode" class="form-control" required>
                            <option value="agence">Agence</option>
                            <option value="en_ligne">En ligne</option>
                            <option value="telephone">Telephone</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID utilisateur</label>
                        <input type="number" name="user_id" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ID livraison</label>
                        <input type="number" name="livraison_id" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnAddCommande" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCommandeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/commandeController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier une commande</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_commande_id">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="edit_commande_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" id="edit_commande_statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="confirmee">Confirmee</option>
                            <option value="payee">Payee</option>
                            <option value="annulee">Annulee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Methode</label>
                        <select name="methode" id="edit_commande_methode" class="form-control" required>
                            <option value="agence">Agence</option>
                            <option value="en_ligne">En ligne</option>
                            <option value="telephone">Telephone</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID utilisateur</label>
                        <input type="number" name="user_id" id="edit_commande_user_id" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ID livraison</label>
                        <input type="number" name="livraison_id" id="edit_commande_livraison_id" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdateCommande" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCommandeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/commandeController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_commande_id">
                    <p>Voulez-vous vraiment supprimer cette commande ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnDeleteCommande" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
