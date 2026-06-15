<?php
$roleId = $_SESSION['user_role_id'] ?? null;
?>

<!-- SIDEBAR -->
<div class="quixnav senlogis-sidebar">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <?php if ($roleId == 1): ?>
            <li>
                <a href="/SenLogis/clientDashboard" aria-expanded="false">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/clientConteneurs" aria-expanded="false">
                    <i class="fa fa-cube"></i>
                    <span class="nav-text">Conteneurs dispo</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/clientDemandes" aria-expanded="false">
                    <i class="fa fa-truck"></i>
                    <span class="nav-text">Mes demandes</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/clientPaiements" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Mes paiements</span>
                </a>
            </li>
            <?php elseif ($roleId == 2): ?>
            <li>
                <a href="/SenLogis/dashboardProprietaire" aria-expanded="false">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-text">Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/conteneursProprietaire" aria-expanded="false">
                    <i class="fa fa-cube"></i>
                    <span class="nav-text">Mes conteneurs</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/demandesProprietaire" aria-expanded="false">
                    <i class="fa fa-truck"></i>
                    <span class="nav-text">Demandes recues</span>
                </a>
            </li>
            <li>
                <a href="/SenLogis/paiementsProprietaire" aria-expanded="false">
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
            <?php endif; ?>
        </ul>
    </div>
</div>
