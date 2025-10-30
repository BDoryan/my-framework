<?php
$products = [
        ["id" => 1, "name" => "T-shirt noir", "category" => "vetement", "price" => 25, "img" => "https://picsum.photos/200?1"],
        ["id" => 2, "name" => "Sweat gris", "category" => "vetement", "price" => 45, "img" => "https://picsum.photos/200?2"],
        ["id" => 3, "name" => "Casquette", "category" => "accessoire", "price" => 15, "img" => "https://picsum.photos/200?3"],
        ["id" => 4, "name" => "Sac à dos", "category" => "accessoire", "price" => 60, "img" => "https://picsum.photos/200?4"],
        ["id" => 5, "name" => "Sneakers", "category" => "chaussure", "price" => 80, "img" => "https://picsum.photos/200?5"],
];
?>
<div class="min-vh-100 d-flex flex-column" style="background: linear-gradient(135deg, #0d6efd 0%, #6610f2 35%, #6f42c1 100%);">
    <section class="container py-5 text-center text-light">
        <span class="badge bg-dark text-uppercase px-3 py-2 mb-3">Démo interactive</span>
        <h1 class="display-4 fw-bold">Bibliothèque de composants</h1>
        <p class="lead mb-4">Découvrez la palette de composants réutilisables du framework à travers un parcours guidé.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#widgets" class="btn btn-light btn-lg px-4">Explorer les widgets</a>
            <a href="#dashboards" class="btn btn-outline-light btn-lg px-4">Voir les tableaux de bord</a>
        </div>
    </section>

    <section class="container flex-grow-1 pb-5">
        <div id="widgets" class="bg-white rounded-4 shadow-lg p-4 p-lg-5 mb-5">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-2">Widget météo en direct</h2>
                    <p class="text-muted mb-0">Mettez en avant les conditions locales pour différentes équipes ou points de vente.</p>
                </div>
                <span class="badge bg-primary text-white px-3 py-2">Données temps réel</span>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="border rounded-4 h-100 p-3 p-lg-4 shadow-sm">
                        <h3 class="h5 text-primary mb-3">Paris</h3>
                        <weather-widget city="Paris"></weather-widget>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-4 h-100 p-3 p-lg-4 shadow-sm">
                        <h3 class="h5 text-primary mb-3">Lyon</h3>
                        <weather-widget city="Lyon"></weather-widget>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-4 h-100 p-3 p-lg-4 shadow-sm">
                        <h3 class="h5 text-primary mb-3">Marseille</h3>
                        <weather-widget city="Marseille"></weather-widget>
                    </div>
                </div>
            </div>
        </div>

        <div id="dashboards" class="bg-white rounded-4 shadow-lg p-4 p-lg-5 mb-5">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-2">Tableau de bord projet</h2>
                    <p class="text-muted mb-0">Suivez l'avancement de vos équipes grâce à un aperçu synthétique et actionnable.</p>
                </div>
                <span class="badge bg-success text-white px-3 py-2">Collaboration</span>
            </div>
            <task-dashboard title="Projet Micro-Framework"></task-dashboard>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-lg p-4 p-lg-5 h-100">
                    <h2 class="h4 mb-3">Galerie produits</h2>
                    <p class="text-muted">Présentez les nouveautés ou un catalogue complet avec pagination, filtres et promotion.</p>
                    <product-gallery
                            title="Catalogue PHP"
                            products='<?= json_to_html_attribute_value($products) ?>'>
                    </product-gallery>

                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white rounded-4 shadow-lg p-4 p-lg-5 h-100">
                    <h2 class="h4 mb-3">Listes « to-do »</h2>
                    <p class="text-muted">Affichez rapidement les priorités du jour ou répartissez les tâches par thématique.</p>
                    <div class="row g-4">
                        <div class="col-12">
                            <todo-list title="Tâches du jour"></todo-list>
                        </div>
                        <div class="col-12">
                            <todo-list title="Courses à faire"></todo-list>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-4 shadow-lg p-4 p-lg-5 mt-5">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h4 mb-2">Cartes utilisateurs</h2>
                    <p class="text-muted mb-0">Offrez une vue synthétique des collaborateurs ou clients avec des indicateurs clés.</p>
                </div>
                <span class="badge bg-warning text-dark px-3 py-2">Profils</span>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded-4 h-100 p-4 shadow-sm">
                        <user-card name="Alice" age="30"></user-card>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-4 h-100 p-4 shadow-sm">
                        <user-card name="Bob" age="25"></user-card>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="container pb-5 text-white-50">
        <div class="row g-4">
            <div class="col-lg-6">
                <h2 class="h4 text-light mb-3">Prêt à intégrer ces composants&nbsp;?</h2>
                <p class="mb-0">Combinez-les pour construire des applications robustes et cohérentes en quelques minutes.</p>
            </div>
            <div class="col-lg-6 d-flex align-items-center justify-content-lg-end">
                <div class="d-flex flex-wrap gap-3">
                    <a href="/docs" class="btn btn-outline-light btn-lg px-4">Documentation</a>
                    <a href="/support" class="btn btn-light btn-lg px-4 text-primary">Contacter l'équipe</a>
                </div>
            </div>
        </div>
    </footer>
</div>
