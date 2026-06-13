<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

require_once __DIR__ . '/../../../../model/userDB.php';

$users = getAllUsersForAdmin();
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
                    <h4>Gestion des utilisateurs</h4>
                    <p class="text-muted">Utilisateurs, roles et etat des comptes.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Utilisateurs</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    Ajouter un utilisateur
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
                                <th>Prenom</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                <th>Role</th>
                                <th>Etat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Il n'existe pour le moment aucun utilisateur.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['telephone'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($user['role_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($user['etat']); ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning btn-edit-user"
                                                data-toggle="modal"
                                                data-target="#editUserModal"
                                                data-id="<?php echo htmlspecialchars($user['id']); ?>"
                                                data-prenom="<?php echo htmlspecialchars($user['prenom']); ?>"
                                                data-nom="<?php echo htmlspecialchars($user['nom']); ?>"
                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                data-telephone="<?php echo htmlspecialchars($user['telephone']); ?>"
                                                data-role-id="<?php echo htmlspecialchars($user['role_id']); ?>">
                                                Modifier
                                            </button>

                                            <?php if ($user['etat'] === 'Actif'): ?>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-danger btn-block-user"
                                                    data-toggle="modal"
                                                    data-target="#blockUserModal"
                                                    data-id="<?php echo htmlspecialchars($user['id']); ?>"
                                                    data-nom="<?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>">
                                                    Bloquer
                                                </button>
                                            <?php else: ?>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-success btn-activate-user"
                                                    data-toggle="modal"
                                                    data-target="#activateUserModal"
                                                    data-id="<?php echo htmlspecialchars($user['id']); ?>"
                                                    data-nom="<?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>">
                                                    Reactiver
                                                </button>
                                            <?php endif; ?>
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

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/userController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Prenom</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telephone</label>
                        <input type="tel" name="telephone" class="form-control" placeholder="+221 77 000 00 00">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role_id" class="form-control" required>
                            <option value="1">Client</option>
                            <option value="2">Proprietaire</option>
                            <option value="3">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe temporaire</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnAddUser" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/userController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier un utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_user_id">
                    <div class="form-group">
                        <label>Prenom</label>
                        <input type="text" name="prenom" id="edit_user_prenom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" id="edit_user_nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_user_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telephone</label>
                        <input type="tel" name="telephone" id="edit_user_telephone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role_id" id="edit_user_role_id" class="form-control" required>
                            <option value="1">Client</option>
                            <option value="2">Proprietaire</option>
                            <option value="3">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnUpdateUser" class="btn btn-primary">Mettre a jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="blockUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/userController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Bloquer un utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="block_user_id">
                    <p>Voulez-vous bloquer <strong id="block_user_nom"></strong> ? Il ne pourra plus se connecter.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnBlockUser" class="btn btn-danger">Bloquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="activateUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/SenLogis/controller/userController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Reactiver un utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="activate_user_id">
                    <p>Voulez-vous reactiver <strong id="activate_user_nom"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="btnActivateUser" class="btn btn-success">Reactiver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-user').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_user_id').value = this.dataset.id;
            document.getElementById('edit_user_prenom').value = this.dataset.prenom;
            document.getElementById('edit_user_nom').value = this.dataset.nom;
            document.getElementById('edit_user_email').value = this.dataset.email;
            document.getElementById('edit_user_telephone').value = this.dataset.telephone;
            document.getElementById('edit_user_role_id').value = this.dataset.roleId;
        });
    });

    document.querySelectorAll('.btn-block-user').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('block_user_id').value = this.dataset.id;
            document.getElementById('block_user_nom').textContent = this.dataset.nom;
        });
    });

    document.querySelectorAll('.btn-activate-user').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('activate_user_id').value = this.dataset.id;
            document.getElementById('activate_user_nom').textContent = this.dataset.nom;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
