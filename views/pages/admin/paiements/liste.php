<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

require_once __DIR__ . '/../../../../model/paiementDB.php';
require_once __DIR__ . '/../../../../model/commandeDB.php';

$paiements = getAllPaiements();
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
                    <h4>Gestion des paiements</h4>
                    <p class="text-muted">Paiements simules: montant, methode, statut et reference.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Paiements</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPaiementModal">
                    Ajouter un paiement
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
                                <th>Montant</th>
                                <th>Methode</th>
                                <th>Statut</th>
                                <th>Reference</th>
                                <th>Commande</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($paiements)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Il n'existe pour le moment aucun paiement.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($paiements as $paiement): ?>
                                    <?php
                                        $clientNom = trim($paiement['user_prenom'] . ' ' . $paiement['user_nom']);
                                        $commandeLabel = '#' . $paiement['commande_id'] . ' - ' . $clientNom . ' / ' . $paiement['conteneur_nom'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($paiement['id']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($paiement['montant'], 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo htmlspecialchars($paiement['methode']); ?></td>
                                        <td><?php echo htmlspecialchars($paiement['statut']); ?></td>
                                        <td><?php echo htmlspecialchars($paiement['reference']); ?></td>
                                        <td><?php echo htmlspecialchars($commandeLabel); ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning btn-edit-paiement"
                                                data-toggle="modal"
                                                data-target="#editPaiementModal"
                                                data-id="<?php echo htmlspecialchars($paiement['id']); ?>"
                                                data-montant="<?php echo htmlspecialchars($paiement['montant']); ?>"
                                                data-methode="<?php echo htmlspecialchars($paiement['methode']); ?>"
                                                data-statut="<?php echo htmlspecialchars($paiement['statut']); ?>"
                                                data-reference="<?php echo htmlspecialchars($paiement['reference']); ?>"
                                                data-commande-id="<?php echo htmlspecialchars($paiement['commande_id']); ?>">
                                                Modifier
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger btn-delete-paiement"
                                                data-toggle="modal"
                                                data-target="#deletePaiementModal"
                                                data-id="<?php echo htmlspecialchars($paiement['id']); ?>"
                                                data-reference="<?php echo htmlspecialchars($paiement['reference']); ?>">
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

<div class="modal fade" id="addPaiementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/paiementController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un paiement simule</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Montant</label>
                        <input type="number" name="montant" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Methode</label>
                        <select name="methode" class="form-control" required>
                            <option value="Wave">Wave</option>
                            <option value="Orange Money">Orange Money</option>
                            <option value="Especes">Especes</option>
                            <option value="Virement">Virement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="valide">Valide</option>
                            <option value="echoue">Echoue</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reference</label>
                        <input type="text" name="reference" class="form-control" placeholder="PAY-20260610-001" required>
                    </div>
                    <div class="form-group">
                        <label>Commande</label>
                        <select name="commande_id" class="form-control" required>
                            <option value="">Choisir une commande</option>
                            <?php foreach ($commandes as $commande): ?>
                                <?php
                                    $clientNom = trim($commande['user_prenom'] . ' ' . $commande['user_nom']);
                                    $commandeLabel = '#' . $commande['id'] . ' - ' . $clientNom . ' / ' . $commande['conteneur_nom'];
                                ?>
                                <option value="<?php echo htmlspecialchars($commande['id']); ?>">
                                    <?php echo htmlspecialchars($commandeLabel); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnAddPaiement" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPaiementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/paiementController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier un paiement</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_paiement_id">
                    <div class="form-group">
                        <label>Montant</label>
                        <input type="number" name="montant" id="edit_paiement_montant" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Methode</label>
                        <select name="methode" id="edit_paiement_methode" class="form-control" required>
                            <option value="Wave">Wave</option>
                            <option value="Orange Money">Orange Money</option>
                            <option value="Especes">Especes</option>
                            <option value="Virement">Virement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut" id="edit_paiement_statut" class="form-control" required>
                            <option value="en_attente">En attente</option>
                            <option value="valide">Valide</option>
                            <option value="echoue">Echoue</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reference</label>
                        <input type="text" name="reference" id="edit_paiement_reference" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Commande</label>
                        <select name="commande_id" id="edit_paiement_commande_id" class="form-control" required>
                            <option value="">Choisir une commande</option>
                            <?php foreach ($commandes as $commande): ?>
                                <?php
                                    $clientNom = trim($commande['user_prenom'] . ' ' . $commande['user_nom']);
                                    $commandeLabel = '#' . $commande['id'] . ' - ' . $clientNom . ' / ' . $commande['conteneur_nom'];
                                ?>
                                <option value="<?php echo htmlspecialchars($commande['id']); ?>">
                                    <?php echo htmlspecialchars($commandeLabel); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdatePaiement" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePaiementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/paiementController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_paiement_id">
                    <p>Voulez-vous vraiment supprimer le paiement <strong id="delete_paiement_reference"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnDeletePaiement" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-paiement').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_paiement_id').value = this.dataset.id;
            document.getElementById('edit_paiement_montant').value = this.dataset.montant;
            document.getElementById('edit_paiement_methode').value = this.dataset.methode;
            document.getElementById('edit_paiement_statut').value = this.dataset.statut;
            document.getElementById('edit_paiement_reference').value = this.dataset.reference;
            document.getElementById('edit_paiement_commande_id').value = this.dataset.commandeId;
        });
    });

    document.querySelectorAll('.btn-delete-paiement').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('delete_paiement_id').value = this.dataset.id;
            document.getElementById('delete_paiement_reference').textContent = this.dataset.reference;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
