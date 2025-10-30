<?php

namespace MyFramework\Controllers;

use MyFramework\Controller\Controller;
use MyFramework\MyFramework;
use MyFramework\Renderer\Page\Page;
use MyFramework\Renderer\Screen\Screen;
use MyFramework\Resources\Link;
use MyFramework\Resources\Script;
use MyFramework\Router\Router;
use MyFramework\Template\Template;

class ScreenController extends Controller
{

    public static function routes(Router $router): void
    {
        $router->get('/', [self::class, 'screen']);
        $router->get('/{screen}', [self::class, 'screen']);
    }

    public static function screen(?string $screen = null): void
    {
        $page = new Page((($screen ?? '') ?: '/index'));
        $page->title('Welcome to MyFramework');
        $page->description('This is the homepage of MyFramework.');
        $page->keywords(['MyFramework', 'PHP', 'Framework', 'Homepage']);
        $page->author('MyFramework Team');

        /**
         * Add links to be included in the head
         * @var Link[] $links_head
         */
        $page->addHeadRenderer(
            Link::make()
                ->href('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css')
                ->rel('stylesheet')
                ->crossOrigin('anonymous')
                ->integrity('sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB')
        );

        /**
         * Add scripts to be included at the bottom of the body
         * @var Script[] $scripts_bottom_body
         */
        $page->addBottomRenderer(
            Script::make()
                ->src('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js')
                ->integrity('sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI')
                ->crossOrigin('anonymous')
        );

        echo $page->render();
        die;
    }
}