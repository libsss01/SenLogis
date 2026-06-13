<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}


require_once $_SERVER['DOCUMENT_ROOT'] . '/SenLogis/model/commandeDB.php';
$commandes = getAllCommandes();
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
                    <p class="text-muted">Commandes clients, statuts et méthode de commande.</p>
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
                                <th>Numéro</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Client</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($commandes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Aucune commande trouvée.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($commandes as $cmd): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cmd['id']); ?></td>
                                    <td><strong>CMD-<?php echo htmlspecialchars($cmd['id']); ?></strong></td> 
                                    <td><?php echo htmlspecialchars($cmd['date'] ?? 'Non définie'); ?></td> 
                                    <td>
                                        <span class="badge badge-primary"><?php echo htmlspecialchars($cmd['statut']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($cmd['user_prenom'] . ' ' . $cmd['user_nom']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCommandeModal" onclick="populateEditModal(<?php echo htmlspecialchars(json_encode($cmd)); ?>)">Modifier</button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteCommandeModal" onclick="document.getElementById('delete_commande_id').value = <?php echo $cmd['id']; ?>">Supprimer</button>
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
                        <label>Numéro de Commande</label>
                        <input type="text" name="numero" class="form-control" placeholder="Ex: CMD-2026-001" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="confirmee">Confirmée</option>
                            <option value="payee">Payée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID Client (Utilisateur)</label>
                        <input type="number" name="user_id" class="form-control" required>
                    </div>
                </div>
                <div class="modal-header">
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
                        <label>Numéro de Commande</label>
                        <input type="text" name="numero" id="edit_commande_numero" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_commande_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" id="edit_commande_statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="confirmee">Confirmée</option>
                            <option value="payee">Payée</option>
                            <option value="annulee">Annullée</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID Client</label>
                        <input type="number" name="user_id" id="edit_commande_user_id" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdateCommande" class="btn btn-primary">Mettre à jour</button>
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
<script>

function populateEditModal(commande) {
    document.getElementById('edit_commande_id').value = commande.id;
    document.getElementById('edit_commande_numero').value = commande.numero || '';
    document.getElementById('edit_commande_description').value = commande.description || '';
    document.getElementById('edit_commande_statut').value = commande.statut;
    document.getElementById('edit_commande_user_id').value = commande.user_id;
}
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>

<script>
function populateEditModal(commande) {
    document.getElementById('edit_commande_id').value = commande.id;
    document.getElementById('edit_commande_numero').value = commande.numero;
    document.getElementById('edit_commande_description').value = commande.description;
    document.getElementById('edit_commande_statut').value = commande.statut;
    document.getElementById('edit_commande_user_id').value = commande.user_id;
}
</script>