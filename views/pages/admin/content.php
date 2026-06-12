<div class="content-body senlogis-dashboard">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-8 p-md-0">
                <div class="welcome-text">
                    <h4>Tableau de bord SenLogis</h4>
                    <p class="text-muted mb-0">Suivi et gestion des conteneurs, livraisons, commandes et paiements.</p>
                </div>
            </div>
            <div class="col-sm-4 p-md-0 text-sm-right mt-3 mt-sm-0">
                <span class="senlogis-badge">Contexte Dakar / Sénégal</span>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Conteneurs</p>
                            <h3>5</h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-cube"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Livraisons</p>
                            <h3>5</h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-truck"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Commandes</p>
                            <h3>5</h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-shopping-cart"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="card senlogis-stat">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Paiements simulés</p>
                            <h3>3</h3>
                        </div>
                        <span class="stat-icon"><i class="fa fa-credit-card"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Modules de gestion</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a class="senlogis-link-card" href="/SenLogis/listeUsers">
                                    <i class="fa fa-users"></i>
                                    Utilisateurs et rôles
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a class="senlogis-link-card" href="/SenLogis/listeConteneurs">
                                    <i class="fa fa-cube"></i>
                                    Conteneurs et positions
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a class="senlogis-link-card" href="/SenLogis/listeLivraisons">
                                    <i class="fa fa-truck"></i>
                                    Livraisons et statuts
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a class="senlogis-link-card" href="/SenLogis/listeCommandes">
                                    <i class="fa fa-shopping-cart"></i>
                                    Commandes clients
                                </a>
                            </div>
                            <div class="col-md-6 mb-3 mb-md-0">
                                <a class="senlogis-link-card" href="/SenLogis/listePaiements">
                                    <i class="fa fa-credit-card"></i>
                                    Paiements simulés
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a class="senlogis-link-card" href="/SenLogis/listeNotes">
                                    <i class="fa fa-star"></i>
                                    Notes utilisateurs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Priorité de développement</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <strong>1. Conteneurs</strong>
                            <p class="text-muted mb-0">Finir ajouter, modifier, supprimer, puis tester les messages flash.</p>
                        </div>
                        <div class="mb-4">
                            <strong>2. Livraisons</strong>
                            <p class="text-muted mb-0">Relier une livraison à un utilisateur et à un conteneur.</p>
                        </div>
                        <div>
                            <strong>3. Paiements</strong>
                            <p class="text-muted mb-0">Faire une simulation simple: montant, méthode et statut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Résumé soutenance</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            Cette partie admin montre comment les classes du diagramme UML sont transformées en tables,
                            modèles, contrôleurs et vues PHP natives. Les paiements sont simulés pour respecter le délai
                            du projet tout en gardant une logique proche d'un vrai flux métier.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
