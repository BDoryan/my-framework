<?php

use MyFramework\Router\Router;

$is_active = function (string $path): bool {
    $current_path = Router::getRequestPath();
    return rtrim($current_path, '/') === rtrim($path, '/');
};

$links = [
        ['label' => 'Accueil', 'path' => '/'],
        ['label' => 'Profil', 'path' => '/profile'],
        ['label' => 'Test de composants', 'path' => '/test_component'],
];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/">
            <?= $name ?? 'Mon framework' ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Basculer la navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($links as $link) {
                    $active_class = $is_active($link['path']) ? ' active' : ''; ?>
                    <li class="nav-item">
                        <a data-use-framework class="<?= merge_classes('nav-link', $active_class) ?>"
                           href="<?= htmlspecialchars($link['path']) ?>"><?= htmlspecialchars($link['label']) ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>