<?php
session_start();
require_once __DIR__ . '/../../../../controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

$_SESSION['error'] = 'Acces non autorise : les administrateurs ne peuvent pas consulter les commandes.';
header('Location: /SenLogis/admin');
exit;

require_once __DIR__ . '/../../../../model/commandeDB.php';
require_once __DIR__ . '/../../../../model/userDB.php';
require_once __DIR__ . '/../../../../model/livraisonDB.php';

$commandes = getAllCommandes();
$clients = getUsersByRole(1);
$livraisons = getAllLivraisons();
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
                    <p class="text-muted">Consultation des commandes clients, statuts et methodes de commande.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Commandes</h4>
                <button type="button" class="btn btn-secondary" disabled aria-disabled="true" title="Action non autorisee pour un administrateur">
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
                            <?php if (empty($commandes)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Il n'existe pour le moment aucune commande.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($commandes as $commande): ?>
                                    <?php
                                        $clientNom = trim($commande['user_prenom'] . ' ' . $commande['user_nom']);
                                        $livraisonLabel = '#' . $commande['livraison_id'] . ' - ' . $commande['livraison_adresse'] . ' / ' . $commande['conteneur_nom'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($commande['id']); ?></td>
                                        <td><?php echo htmlspecialchars($commande['date']); ?></td>
                                        <td><?php echo htmlspecialchars($commande['statut']); ?></td>
                                        <td><?php echo htmlspecialchars($commande['methode']); ?></td>
                                        <td><?php echo htmlspecialchars($clientNom); ?></td>
                                        <td><?php echo htmlspecialchars($livraisonLabel); ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary btn-edit-commande"
                                                disabled
                                                aria-disabled="true"
                                                title="Action non autorisee pour un administrateur"
                                                data-toggle="modal"
                                                data-target="#editCommandeModal"
                                                data-id="<?php echo htmlspecialchars($commande['id']); ?>"
                                                data-date="<?php echo htmlspecialchars($commande['date']); ?>"
                                                data-statut="<?php echo htmlspecialchars($commande['statut']); ?>"
                                                data-methode="<?php echo htmlspecialchars($commande['methode']); ?>"
                                                data-user-id="<?php echo htmlspecialchars($commande['user_id']); ?>"
                                                data-livraison-id="<?php echo htmlspecialchars($commande['livraison_id']); ?>">
                                                Modifier
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary btn-delete-commande"
                                                disabled
                                                aria-disabled="true"
                                                title="Action non autorisee pour un administrateur"
                                                data-toggle="modal"
                                                data-target="#deleteCommandeModal"
                                                data-id="<?php echo htmlspecialchars($commande['id']); ?>"
                                                data-label="<?php echo htmlspecialchars('#' . $commande['id'] . ' - ' . $clientNom); ?>">
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
                        <label>Client</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Choisir un client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo htmlspecialchars($client['id']); ?>">
                                    <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Livraison</label>
                        <select name="livraison_id" class="form-control" required>
                            <option value="">Choisir une livraison</option>
                            <?php foreach ($livraisons as $livraison): ?>
                                <option value="<?php echo htmlspecialchars($livraison['id']); ?>">
                                    <?php echo htmlspecialchars('#' . $livraison['id'] . ' - ' . $livraison['adresse'] . ' / ' . $livraison['conteneur_nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                        <label>Client</label>
                        <select name="user_id" id="edit_commande_user_id" class="form-control" required>
                            <option value="">Choisir un client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo htmlspecialchars($client['id']); ?>">
                                    <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Livraison</label>
                        <select name="livraison_id" id="edit_commande_livraison_id" class="form-control" required>
                            <option value="">Choisir une livraison</option>
                            <?php foreach ($livraisons as $livraison): ?>
                                <option value="<?php echo htmlspecialchars($livraison['id']); ?>">
                                    <?php echo htmlspecialchars('#' . $livraison['id'] . ' - ' . $livraison['adresse'] . ' / ' . $livraison['conteneur_nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                    <p>Voulez-vous vraiment supprimer la commande <strong id="delete_commande_label"></strong> ?</p>
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
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-commande').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_commande_id').value = this.dataset.id;
            document.getElementById('edit_commande_date').value = this.dataset.date;
            document.getElementById('edit_commande_statut').value = this.dataset.statut;
            document.getElementById('edit_commande_methode').value = this.dataset.methode;
            document.getElementById('edit_commande_user_id').value = this.dataset.userId;
            document.getElementById('edit_commande_livraison_id').value = this.dataset.livraisonId;
        });
    });

    document.querySelectorAll('.btn-delete-commande').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('delete_commande_id').value = this.dataset.id;
            document.getElementById('delete_commande_label').textContent = this.dataset.label;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
