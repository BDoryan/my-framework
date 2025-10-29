<?php

use MyFramework\Renderer\Screen\Screen;
use MyFramework\Resources\Link;
use MyFramework\Resources\Script;
use MyFramework\Template\Template;
use MyFramework\MyFramework;

// Load vendor autoload file
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/functions.php';

// Initialize the framework
MyFramework::initialize();

/**
 * Add links to be included in the head
 * @var Link[] $links_head
 */
$links_head = [
    Link::make()
        ->href('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css')
        ->rel('stylesheet')
        ->crossOrigin('anonymous')
        ->integrity('sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB')
];

/**
 * Add scripts to be included in the head
 * @var Script[] $scripts_head
 */
$scripts_head = [];
if(MyFramework::isDevelopmentMode()) {
    $scripts_head[] = Script::make()
        ->src('/resources/js/__dev/dev-tools.js')
        ->type('module');
}

/**
 * Add scripts to be included at the bottom of the body
 * @var Script[] $scripts_bottom_body
 */
$scripts_bottom_body = [
    Script::make()
        ->src('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js')
        ->integrity('sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI')
        ->crossOrigin('anonymous')
];

// Screen home
$screen = Screen::create('/screens/home')
    ->addBottomRenderers($scripts_bottom_body);

// Load and render the template
Template::setRootPath(__DIR__ . '/../templates');
Template::printTemplate('page', [
    'links_head' => $links_head,
    'scripts_head' => $scripts_head,
    'content' => Template::loadTemplate('/partials/header', [
            'title' => 'Home'
        ]) . $screen->render()
]);
