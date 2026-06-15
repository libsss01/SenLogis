<?php
session_start();
require_once __DIR__ . '/../../../../controller/sessionSecurity.php';
sendNoCacheHeaders();

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
                    <p class="text-muted">Suivi des comptes utilisateurs et de leur etat d'acces.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Utilisateurs</h4>
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
