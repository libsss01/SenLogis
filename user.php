<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/model/conteneurDB.php';
require_once __DIR__ . '/model/livraisonDB.php';
require_once __DIR__ . '/model/commandeDB.php';
require_once __DIR__ . '/model/paiementDB.php';
require_once __DIR__ . '/model/noteDB.php';

$conteneursDisponibles = getConteneursDisponibles();
$livraisons = getLivraisonsByUser($_SESSION['user_id']);
$commandes = getCommandesByUser($_SESSION['user_id']);
$paiements = getPaiementsByUser($_SESSION['user_id']);
$notes = getNotesByUser($_SESSION['user_id']);
$notesByLivraison = [];

foreach ($notes as $note) {
    $notesByLivraison[$note['numLivraison']] = $note;
}

$totalCommandes = count($commandes);
$totalPaiements = count($paiements);
$totalDepense = 0;
$livraisonsEnCours = 0;
$livraisonsLivrees = 0;

foreach ($paiements as $paiement) {
    if ($paiement['statut'] === 'valide') {
        $totalDepense += (float) $paiement['montant'];
    }
}

foreach ($livraisons as $livraison) {
    if (in_array($livraison['statut'], ['en_attente', 'validee', 'en_cours'], true)) {
        $livraisonsEnCours++;
    }

    if ($livraison['statut'] === 'livree') {
        $livraisonsLivrees++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Client - SenLogis</title>
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

    <div class="content-body senlogis-dashboard owner-dashboard client-dashboard">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-8 p-md-0">
                    <div class="welcome-text">
                        <h4>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                        <p class="text-muted">Consultez les conteneurs disponibles et lancez une demande de livraison.</p>
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
                            <div class="owner-stat-icon"><i class="fa fa-shopping-cart"></i></div>
                            <div>
                                <span>Commandes</span>
                                <strong><?php echo htmlspecialchars($totalCommandes); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card senlogis-owner-stat">
                        <div class="card-body">
                            <div class="owner-stat-icon"><i class="fa fa-truck"></i></div>
                            <div>
                                <span>Demandes</span>
                                <strong><?php echo htmlspecialchars($livraisonsEnCours); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card senlogis-owner-stat">
                        <div class="card-body">
                            <div class="owner-stat-icon"><i class="fa fa-check"></i></div>
                            <div>
                                <span>Livrees</span>
                                <strong><?php echo htmlspecialchars($livraisonsLivrees); ?></strong>
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

            <div class="row" id="conteneurs-disponibles">
                <div class="col-lg-12">
                    <div class="card owner-section-card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Conteneurs disponibles</h4>
                                <small class="text-muted">Choisissez un conteneur pour lancer une demande de livraison.</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (empty($conteneursDisponibles)): ?>
                                    <div class="col-12">
                                        <div class="owner-empty-state">
                                            <i class="fa fa-cube"></i>
                                            <p>Aucun conteneur disponible pour le moment.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($conteneursDisponibles as $conteneur): ?>
                                        <div class="col-xl-4 col-md-6">
                                            <div class="owner-container-card">
                                                <img src="/SenLogis/public/img/container-card.svg" alt="Illustration conteneur">
                                                <div class="owner-container-content">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <span class="owner-card-label">Disponible</span>
                                                            <h5><?php echo htmlspecialchars($conteneur['nom']); ?></h5>
                                                        </div>
                                                        <span class="owner-status status-disponible">Disponible</span>
                                                    </div>
                                                    <div class="owner-card-meta">
                                                        <div>
                                                            <i class="fa fa-map-marker"></i>
                                                            <span><?php echo htmlspecialchars($conteneur['position']); ?></span>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-user"></i>
                                                            <span><?php echo htmlspecialchars($conteneur['proprietaire_prenom'] . ' ' . $conteneur['proprietaire_nom']); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="client-card-actions">
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-primary btn-sm btn-detail-conteneur"
                                                            data-toggle="modal"
                                                            data-target="#detailConteneurModal"
                                                            data-nom="<?php echo htmlspecialchars($conteneur['nom']); ?>"
                                                            data-position="<?php echo htmlspecialchars($conteneur['position']); ?>"
                                                            data-proprietaire="<?php echo htmlspecialchars($conteneur['proprietaire_prenom'] . ' ' . $conteneur['proprietaire_nom']); ?>">
                                                            Details
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-primary btn-sm btn-commander-conteneur"
                                                            data-toggle="modal"
                                                            data-target="#commandeConteneurModal"
                                                            data-id="<?php echo htmlspecialchars($conteneur['id']); ?>"
                                                            data-nom="<?php echo htmlspecialchars($conteneur['nom']); ?>">
                                                            Commander
                                                        </button>
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

            <div class="row" id="mes-demandes">
                <div class="col-lg-12">
                    <div class="card owner-section-card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title mb-1">Mes demandes recentes</h4>
                            <small class="text-muted">Suivez l'etat de vos commandes et livraisons.</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Adresse</th>
                                            <th>Date livraison</th>
                                            <th>Conteneur</th>
                                            <th>Commande</th>
                                            <th>Livraison</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($commandes)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Aucune demande pour le moment.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($commandes as $commande): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($commande['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($commande['adresse']); ?></td>
                                                    <td><?php echo htmlspecialchars($commande['dateLivraison']); ?></td>
                                                    <td><?php echo htmlspecialchars($commande['conteneur_nom']); ?></td>
                                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($commande['statut']); ?></span></td>
                                                    <td><span class="badge badge-primary"><?php echo htmlspecialchars($commande['livraison_statut']); ?></span></td>
                                                    <td>
                                                        <?php if (isset($notesByLivraison[$commande['livraison_id']])): ?>
                                                            <span class="badge badge-success">
                                                                <?php echo htmlspecialchars($notesByLivraison[$commande['livraison_id']]['note']); ?>/5
                                                            </span>
                                                        <?php elseif ($commande['livraison_statut'] === 'livree'): ?>
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-primary btn-note-livraison"
                                                                data-toggle="modal"
                                                                data-target="#noteLivraisonModal"
                                                                data-livraison-id="<?php echo htmlspecialchars($commande['livraison_id']); ?>"
                                                                data-conteneur="<?php echo htmlspecialchars($commande['conteneur_nom']); ?>">
                                                                Noter
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Apres livraison</span>
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

            <div class="row" id="mes-paiements">
                <div class="col-lg-12">
                    <div class="card owner-section-card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Mes paiements</h4>
                                <small class="text-muted">Paiements simules lies a vos commandes.</small>
                            </div>
                            <span class="senlogis-badge">
                                <?php echo htmlspecialchars(number_format($totalDepense, 0, ',', ' ')); ?> FCFA depenses
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Reference</th>
                                            <th>Commande</th>
                                            <th>Conteneur</th>
                                            <th>Montant</th>
                                            <th>Methode</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($paiements)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Aucun paiement enregistre pour vos commandes.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($paiements as $paiement): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($paiement['reference']); ?></td>
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

    <div class="modal fade" id="detailConteneurModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details du conteneur</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p><strong>Conteneur:</strong> <span id="detail_conteneur_nom"></span></p>
                    <p><strong>Position:</strong> <span id="detail_conteneur_position"></span></p>
                    <p><strong>Proprietaire:</strong> <span id="detail_conteneur_proprietaire"></span></p>
                    <p class="text-muted mb-0">Ce conteneur est disponible pour une demande de livraison.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commandeConteneurModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/SenLogis/controller/clientRequestController.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Commander un conteneur</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="conteneur_id" id="commande_conteneur_id">
                        <p class="mb-3">Conteneur choisi: <strong id="commande_conteneur_nom"></strong></p>
                        <div class="form-group">
                            <label>Adresse de livraison</label>
                            <input type="text" name="adresse" class="form-control" placeholder="Plateau, Dakar" required>
                        </div>
                        <div class="form-group">
                            <label>Date souhaitee</label>
                            <input type="date" name="dateLivraison" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Methode de commande</label>
                            <select name="methode" class="form-control" required>
                                <option value="en_ligne">En ligne</option>
                                <option value="agence">Agence</option>
                                <option value="telephone">Telephone</option>
                            </select>
                        </div>
                        <p class="text-muted mb-0">Votre demande sera transmise au proprietaire du conteneur.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" name="btnCreateClientRequest" class="btn btn-primary">Envoyer la demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="noteLivraisonModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/SenLogis/controller/clientNoteController.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Noter une livraison</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="numLivraison" id="note_livraison_id">
                        <p class="mb-3">Livraison du conteneur: <strong id="note_livraison_conteneur"></strong></p>
                        <div class="form-group">
                            <label>Votre note</label>
                            <select name="note" class="form-control" required>
                                <option value="">Choisir une note</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Tres bien</option>
                                <option value="3">3 - Correct</option>
                                <option value="2">2 - Moyen</option>
                                <option value="1">1 - Insatisfait</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" name="btnAddClientNote" class="btn btn-primary">Enregistrer la note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-detail-conteneur').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('detail_conteneur_nom').textContent = this.dataset.nom;
                document.getElementById('detail_conteneur_position').textContent = this.dataset.position;
                document.getElementById('detail_conteneur_proprietaire').textContent = this.dataset.proprietaire;
            });
        });

        document.querySelectorAll('.btn-commander-conteneur').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('commande_conteneur_id').value = this.dataset.id;
                document.getElementById('commande_conteneur_nom').textContent = this.dataset.nom;
            });
        });

        document.querySelectorAll('.btn-note-livraison').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('note_livraison_id').value = this.dataset.livraisonId;
                document.getElementById('note_livraison_conteneur').textContent = this.dataset.conteneur;
            });
        });
    });
    </script>

    <?php require_once("views/pages/admin/footer.php"); ?>
    <?php require_once("views/pages/admin/scripts.php"); ?>
</body>

</html>
