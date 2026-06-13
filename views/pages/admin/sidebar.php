<?php
$roleId = $_SESSION['user_role_id'] ?? null;
?>

<!-- SIDEBAR -->
<div class="quixnav senlogis-sidebar">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <?php if ($roleId == 1): ?>
            <li>
                <a href="/SenLogis/user.php" aria-expanded="false">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/user.php#conteneurs-disponibles" aria-expanded="false">
                    <i class="fa fa-cube"></i>
                    <span class="nav-text">Conteneurs dispo</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/user.php#mes-demandes" aria-expanded="false">
                    <i class="fa fa-truck"></i>
                    <span class="nav-text">Mes demandes</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/user.php#mes-paiements" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Mes paiements</span>
                </a>
            </li>
            <?php elseif ($roleId == 2): ?>
            <li>
                <a href="/SenLogis/dashboard_owner.php" aria-expanded="false">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/dashboard_owner.php#mes-conteneurs" aria-expanded="false">
                    <i class="fa fa-cube"></i>
                    <span class="nav-text">Mes conteneurs</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/dashboard_owner.php#demandes-recues" aria-expanded="false">
                    <i class="fa fa-truck"></i>
                    <span class="nav-text">Demandes recues</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/dashboard_owner.php#paiements-recus" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Paiements recus</span>
                </a>
            </li>
            <?php else: ?>
            <li>
                <a href="/SenLogis/admin" aria-expanded="false">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/listeUsers" aria-expanded="false">
                    <i class="fa fa-users"></i>
                    <span class="nav-text">Utilisateurs</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/listeConteneurs" aria-expanded="false">
                    <i class="fa fa-cube"></i>
                    <span class="nav-text">Conteneurs</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/listeLivraisons" aria-expanded="false">
                    <i class="fa fa-truck"></i>
                    <span class="nav-text">Livraisons</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/listeCommandes" aria-expanded="false">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="nav-text">Commandes</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/listePaiements" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Paiements</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/listeNotes" aria-expanded="false">
                    <i class="fa fa-star"></i>
                    <span class="nav-text">Notes</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
