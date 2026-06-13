<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

require_once __DIR__ . '/../../../../model/noteDB.php';
require_once __DIR__ . '/../../../../model/userDB.php';
require_once __DIR__ . '/../../../../model/livraisonDB.php';

$notes = getAllNotes();
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
                    <h4>Gestion des notes</h4>
                    <p class="text-muted">Notes donnees par les utilisateurs apres une livraison.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Notes</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNoteModal">
                    Ajouter une note
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
                                <th>Note</th>
                                <th>Livraison</th>
                                <th>Utilisateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($notes)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Il n'existe pour le moment aucune note.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($notes as $note): ?>
                                    <?php
                                        $clientNom = trim($note['user_prenom'] . ' ' . $note['user_nom']);
                                        $livraisonLabel = '#' . $note['numLivraison'] . ' - ' . $note['livraison_adresse'] . ' / ' . $note['conteneur_nom'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($note['id']); ?></td>
                                        <td><?php echo htmlspecialchars($note['note']); ?>/5</td>
                                        <td><?php echo htmlspecialchars($livraisonLabel); ?></td>
                                        <td><?php echo htmlspecialchars($clientNom); ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning btn-edit-note"
                                                data-toggle="modal"
                                                data-target="#editNoteModal"
                                                data-id="<?php echo htmlspecialchars($note['id']); ?>"
                                                data-note="<?php echo htmlspecialchars($note['note']); ?>"
                                                data-num-livraison="<?php echo htmlspecialchars($note['numLivraison']); ?>"
                                                data-user-id="<?php echo htmlspecialchars($note['user_id']); ?>">
                                                Modifier
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger btn-delete-note"
                                                data-toggle="modal"
                                                data-target="#deleteNoteModal"
                                                data-id="<?php echo htmlspecialchars($note['id']); ?>"
                                                data-label="<?php echo htmlspecialchars('#' . $note['id'] . ' - ' . $clientNom); ?>">
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

<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/noteController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une note</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Note</label>
                        <input type="number" name="note" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label>Livraison</label>
                        <select name="numLivraison" class="form-control" required>
                            <option value="">Choisir une livraison</option>
                            <?php foreach ($livraisons as $livraison): ?>
                                <option value="<?php echo htmlspecialchars($livraison['id']); ?>">
                                    <?php echo htmlspecialchars('#' . $livraison['id'] . ' - ' . $livraison['adresse'] . ' / ' . $livraison['conteneur_nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Choisir un utilisateur</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo htmlspecialchars($client['id']); ?>">
                                    <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnAddNote" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/noteController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier une note</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_note_id">
                    <div class="form-group">
                        <label>Note</label>
                        <input type="number" name="note" id="edit_note_note" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label>Livraison</label>
                        <select name="numLivraison" id="edit_note_numLivraison" class="form-control" required>
                            <option value="">Choisir une livraison</option>
                            <?php foreach ($livraisons as $livraison): ?>
                                <option value="<?php echo htmlspecialchars($livraison['id']); ?>">
                                    <?php echo htmlspecialchars('#' . $livraison['id'] . ' - ' . $livraison['adresse'] . ' / ' . $livraison['conteneur_nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <select name="user_id" id="edit_note_user_id" class="form-control" required>
                            <option value="">Choisir un utilisateur</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo htmlspecialchars($client['id']); ?>">
                                    <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdateNote" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNoteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/noteController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_note_id">
                    <p>Voulez-vous vraiment supprimer la note <strong id="delete_note_label"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnDeleteNote" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-note').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_note_id').value = this.dataset.id;
            document.getElementById('edit_note_note').value = this.dataset.note;
            document.getElementById('edit_note_numLivraison').value = this.dataset.numLivraison;
            document.getElementById('edit_note_user_id').value = this.dataset.userId;
        });
    });

    document.querySelectorAll('.btn-delete-note').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('delete_note_id').value = this.dataset.id;
            document.getElementById('delete_note_label').textContent = this.dataset.label;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
