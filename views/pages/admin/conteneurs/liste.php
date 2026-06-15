<?php
session_start();
require_once __DIR__ . '/../../../../controller/sessionSecurity.php';
sendNoCacheHeaders();

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
                    <p class="text-muted">Suivi des conteneurs enregistres, avec protection des informations proprietaire.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Conteneurs</h4>
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
                                <th>Proprietaire</th>
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
                                <tr class="admin-readonly-row">
                                    <td><?php echo htmlspecialchars($conteneur['id']); ?></td>
                                    <td><?php echo htmlspecialchars($conteneur['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($conteneur['statut']); ?></td>
                                    <td><?php echo htmlspecialchars($conteneur['position']); ?></td>
                                    <td>****</td>
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

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php require_once __DIR__ . '/../scripts.php'; ?>
