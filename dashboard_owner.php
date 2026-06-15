<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 2) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/conteneurDB.php';
require_once __DIR__ . '/model/livraisonDB.php';
require_once __DIR__ . '/model/paiementDB.php';

function getConteneurStatutClass($statut)
{
    switch ($statut) {
        case 'disponible':
            return 'status-disponible';
        case 'en_livraison':
            return 'status-livraison';
        case 'maintenance':
            return 'status-maintenance';
        case 'indisponible':
            return 'status-indisponible';
        case 'reserve':
            return 'status-reserve';
        default:
            return 'status-default';
    }
}

function getConteneurStatutLabel($statut)
{
    return ucfirst(str_replace('_', ' ', $statut));
}

$conteneurs = getConteneursByProprietaire($_SESSION['user_id']);
$demandesRecues = getDemandesLivraisonByProprietaire($_SESSION['user_id']);
$paiementsRecus = getPaiementsByProprietaire($_SESSION['user_id']);

$totalConteneurs = count($conteneurs);
$totalPaiements = count($paiementsRecus);
$revenuTotal = 0;
$conteneursEnLivraison = 0;
$conteneursDisponibles = 0;
$conteneursMaintenance = 0;

foreach ($paiementsRecus as $paiement) {
    if ($paiement['statut'] === 'valide') {
        $revenuTotal += (float) $paiement['montant'];
    }
}

foreach ($conteneurs as $conteneur) {
    if ($conteneur['statut'] === 'en_livraison') {
        $conteneursEnLivraison++;
    }

    if ($conteneur['statut'] === 'disponible') {
        $conteneursDisponibles++;
    }

    if ($conteneur['statut'] === 'maintenance') {
        $conteneursMaintenance++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Proprietaire - SenLogis</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/SenLogis/public/template/templateAdmin/focus-2/images/favicon.png">
    <link rel="stylesheet" href="/SenLogis/public/template/templateAdmin/focus-2/vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/SenLogis/public/template/templateAdmin/focus-2/vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/icons/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/icons/avasta/css/style.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/icons/font-awesome-old/css/font-awesome.min.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/icons/material-design-iconic-font/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/icons/themify-icons/css/themify-icons.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/icons/line-awesome/css/line-awesome.min.css" rel="stylesheet">
    <link href="/SenLogis/public/template/templateAdmin/focus-2/css/style.css" rel="stylesheet">
    <link href="/SenLogis/public/css/admin-custom.css?v=20260613-sidebar-2" rel="stylesheet">
</head>

<body>
    <?php require_once("views/pages/admin/preloader.php"); ?>
    <?php require_once("views/pages/admin/nav-header.php"); ?>
    <?php require_once("views/pages/admin/header.php"); ?>
    <?php require_once("views/pages/admin/sidebar.php"); ?>

    <div class="content-body senlogis-dashboard owner-dashboard">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-8 p-md-0">
                    <div class="welcome-text">
                        <h4>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                        <p class="text-muted">Suivez vos conteneurs, leurs statuts et leurs positions.</p>
                    </div>
                </div>
            </div>

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

            <div class="row owner-stat-row">
                <div class="col-lg-3 col-sm-6">
                    <div class="card senlogis-owner-stat">
                        <div class="card-body">
                            <div class="owner-stat-icon"><i class="fa fa-cube"></i></div>
                            <div>
                                <span>Conteneurs</span>
                                <strong><?php echo htmlspecialchars($totalConteneurs); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card senlogis-owner-stat">
                        <div class="card-body">
                            <div class="owner-stat-icon"><i class="fa fa-truck"></i></div>
                            <div>
                                <span>En livraison</span>
                                <strong><?php echo htmlspecialchars($conteneursEnLivraison); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card senlogis-owner-stat">
                        <div class="card-body">
                            <div class="owner-stat-icon"><i class="fa fa-check"></i></div>
                            <div>
                                <span>Disponibles</span>
                                <strong><?php echo htmlspecialchars($conteneursDisponibles); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card senlogis-owner-stat">
                        <div class="card-body">
                            <div class="owner-stat-icon"><i class="fa fa-credit-card"></i></div>
                            <div>
                                <span>Paiements</span>
                                <strong><?php echo htmlspecialchars($totalPaiements); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="mes-conteneurs">
                <div class="col-lg-12">
                    <div class="card owner-section-card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Mes conteneurs</h4>
                                <small class="text-muted">Suivez vos conteneurs associes et leurs positions.</small>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOwnerConteneurModal">
                                Ajouter un conteneur
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (empty($conteneurs)): ?>
                                    <div class="col-12">
                                        <div class="owner-empty-state">
                                            <i class="fa fa-cube"></i>
                                            <p>Aucun conteneur associe a votre compte.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($conteneurs as $conteneur): ?>
                                        <?php
                                            $statutClass = getConteneurStatutClass($conteneur['statut']);
                                            $statutLabel = getConteneurStatutLabel($conteneur['statut']);
                                        ?>
                                        <div class="col-xl-4 col-md-6">
                                            <div class="owner-container-card">
                                                <img src="/SenLogis/public/img/container-card.svg" alt="Illustration conteneur">
                                                <div class="owner-container-content">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <span class="owner-card-label">Conteneur</span>
                                                            <h5><?php echo htmlspecialchars($conteneur['nom']); ?></h5>
                                                        </div>
                                                        <span class="owner-status <?php echo htmlspecialchars($statutClass); ?>">
                                                            <?php echo htmlspecialchars($statutLabel); ?>
                                                        </span>
                                                    </div>
                                                    <div class="owner-card-meta">
                                                        <div>
                                                            <i class="fa fa-map-marker"></i>
                                                            <span><?php echo htmlspecialchars($conteneur['position']); ?></span>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-user"></i>
                                                            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="demandes-recues">
                <div class="col-lg-12">
                    <div class="card owner-section-card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Demandes recues</h4>
                                <small class="text-muted">Acceptez ou refusez les demandes faites sur vos conteneurs.</small>
                            </div>
                            <span class="senlogis-badge"><?php echo htmlspecialchars(count($demandesRecues)); ?> en attente</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Conteneur</th>
                                            <th>Adresse</th>
                                            <th>Date souhaitee</th>
                                            <th>Methode</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($demandesRecues)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Aucune demande en attente.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($demandesRecues as $demande): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($demande['client_prenom'] . ' ' . $demande['client_nom']); ?></td>
                                                    <td><?php echo htmlspecialchars($demande['conteneur_nom']); ?></td>
                                                    <td><?php echo htmlspecialchars($demande['adresse']); ?></td>
                                                    <td><?php echo htmlspecialchars($demande['dateLivraison']); ?></td>
                                                    <td><?php echo htmlspecialchars($demande['commande_methode']); ?></td>
                                                    <td>
                                                        <form action="/SenLogis/controller/ownerRequestController.php" method="POST" class="owner-request-actions">
                                                            <input type="hidden" name="livraison_id" value="<?php echo htmlspecialchars($demande['id']); ?>">
                                                            <button type="submit" name="btnAcceptOwnerRequest" class="btn btn-sm btn-success">Accepter</button>
                                                            <button type="submit" name="btnRejectOwnerRequest" class="btn btn-sm btn-outline-danger">Refuser</button>
                                                        </form>
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

            <div class="row" id="paiements-recus">
                <div class="col-lg-12">
                    <div class="card owner-section-card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Paiements recus</h4>
                                <small class="text-muted">Paiements simules lies aux commandes sur vos conteneurs.</small>
                            </div>
                            <span class="senlogis-badge">
                                <?php echo htmlspecialchars(number_format($revenuTotal, 0, ',', ' ')); ?> FCFA valides
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Reference</th>
                                            <th>Client</th>
                                            <th>Commande</th>
                                            <th>Conteneur</th>
                                            <th>Montant</th>
                                            <th>Methode</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($paiementsRecus)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Aucun paiement lie a vos conteneurs.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($paiementsRecus as $paiement): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($paiement['reference']); ?></td>
                                                    <td><?php echo htmlspecialchars($paiement['client_prenom'] . ' ' . $paiement['client_nom']); ?></td>
                                                    <td>#<?php echo htmlspecialchars($paiement['commande_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($paiement['conteneur_nom']); ?></td>
                                                    <td><?php echo htmlspecialchars(number_format($paiement['montant'], 0, ',', ' ')); ?> FCFA</td>
                                                    <td><?php echo htmlspecialchars($paiement['methode']); ?></td>
                                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($paiement['statut']); ?></span></td>
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

        </div>
    </div>

    <div class="modal fade" id="addOwnerConteneurModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/SenLogis/controller/ownerConteneurController.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un conteneur</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom du conteneur</label>
                            <input type="text" name="nom" class="form-control" placeholder="CONT-DKR-006" required>
                        </div>
                        <div class="form-group">
                            <label>Statut initial</label>
                            <select name="statut" class="form-control" required>
                                <option value="disponible">Disponible</option>
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
                        <button type="submit" name="btnAddOwnerConteneur" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once("views/pages/admin/footer.php"); ?>
    <?php require_once("views/pages/admin/scripts.php"); ?>
</body>

</html>
