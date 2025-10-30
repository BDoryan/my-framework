# Guide developpeur MyFramework

## Apercu du projet

MyFramework est un micro-framework PHP cree pour experimenter la construction d un framework maison. Il assemble un routeur minimaliste, un moteur de templates PHP natif et une serie de classes de rendu qui facilitent la creation d ecrans dynamiques.

## Prerequis

- PHP 8.2 ou plus recent (identique a l image Docker fournie)
- Composer pour gerer l autoload PSR-4
- Extensions PHP standard (aucune dependance externe obligatoire)
- Optionnel : Docker et Docker Compose

## Installation rapide

### Via PHP natif

```bash
composer install
php -S 127.0.0.1:8000 -t public
```

Ensuite ouvrez http://127.0.0.1:8000 dans votre navigateur.

### Via Docker

```bash
docker compose up --build
```

L application sera disponible sur http://127.0.0.1:8080.

## Arborescence principale

```
public/                 Point d entree HTTP et assets front
├── index.php           Lance MyFramework::initialize()
├── resources/js/       Scripts utilitaires (navigation AJAX)
└── __dev/              Outils de fast refresh

src/                    Code du noyau
├── Controllers/        Controleurs applicatifs
├── Logger/             Journalisation fichier + CLI
├── Renderer/           Rend Page, Screen et ressources
├── Resources/          Wrapper pour link/script
├── Router/             Routeur HTTP
├── Template/           Chargement des templates PHP
└── functions.php       Helpers globaux utilises dans les vues

templates/              Vues PHP
├── partials/           Header, footer, body, resources
└── screens/            Ecrans associes aux routes (index, profile, etc.)
```

## Cycle de vie d une requete

1. `public/index.php` charge `src/autoload.php` (Composer) puis appelle `MyFramework::initialize()`.
2. `MyFramework::initialize()` configure le routeur, pointe les templates vers `templates/`, initialise le logger, charge `.env` et enregistre les routes exposees par les controleurs.
3. `Router::dispatch()` fait correspondre la requete HTTP a un handler (closure, `Controller@method` ou tableau `[Classe::class, 'method']`).
4. Les controleurs construisent un objet `Page` ou `Screen`, ajoutent scripts/styles puis rendent la reponse HTML.

## Routage

```php
// src/Controllers/ScreenController.php
public static function routes(Router $router): void
{
    $router->get('/', [self::class, 'screen']);
    $router->get('/{screen}', [self::class, 'screen']);
}
```

- Support des verbes HTTP principaux (`get`, `post`, `put`, `delete`).
- Placeholders `{id}` convertis en regex avec groupe nomme (`(?P<id>...)`).
- `Router::generate()` permet de produire une URL depuis un nom de route.

## Controleurs

- Etendent `MyFramework\Controller\Controller`.
- Doivent definir une methode statique `routes()` pour enregistrer leurs routes.
- Rendent une reponse via `Page` ou `Screen`, par exemple dans `ScreenController::screen()`.

## Rendu des pages et templates

La classe `Page` herite de `Screen` :

- `Screen` charge le template cible (`templates/screens/{screen}.php`) et l entoure avec `templates/partials/body.php`.
- `Page` ajoute la structure HTML globale via `templates/page.php`, insere `partials/header.php` et `partials/footer.php`, puis rend la page complete.

Fonctions clefs :

- `Template::loadTemplate('/partials/header')` inclut un template PHP depuis la racine configuree.
- `RendererUtils::renders()` parcours une liste de renderers et concatene le rendu.
- Helpers globaux (`load_template`, `merge_classes`, `dd`, etc.) disponibles dans `src/functions.php`.

## Ressources front

- `MyFramework\Resources\Link` et `MyFramework\Resources\Script` encapsulent les balises `<link>` et `<script>`.
- Les partials `templates/partials/resources/link.php` et `script.php` produisent le HTML final.
- Ajouter une ressource via :

```php
$page->addHeadRenderer(
    Link::make()
        ->href('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css')
        ->rel('stylesheet')
);
```

## Navigation AJAX

Le script `public/resources/js/framework.js` intercepte les liens possedant `data-use-framework` :

- Effectue une requete `fetch` avec `?_ajax=1`.
- Remplace le contenu du `<body>`.
- Reexecute les scripts inline.
- Met a jour l historique (`history.pushState`).

Pensez a ajouter `data-use-framework` aux liens internes qui doivent profiter de cette navigation progressive.

## Fast refresh et environnement

- Variable `.env` : `MY_FRAMEWORK_ENVIRONMENT=development`.
- Quand le mode developpement est actif, `Page::render()` injecte le script `public/resources/js/__dev/dev-tools.js`.
- Le watcher `public/__dev/fast-refresh.php` surveille les dossiers declares dans `public/__dev/config/watch_path.php` (`src`, `templates`, `public/resources`) et demande un rechargement automatique.

## Journalisation

- `MyFramework\Logger\Logger` ecrit par defaut dans `logs/app.log`.
- `MyFramework::resolveLogDirectory()` tente successivement :
  1. `LOG_DIRECTORY` si defini (variable d environnement)
  2. `./logs` (cree le dossier si besoin)
  3. Un dossier temporaire dans le repertoire systeme
- Les messages sont colores en CLI et stockes sans codes ANSI dans le fichier.

## Ajouter un nouvel ecran

1. Creer un template `templates/screens/ma-page.php`.
2. Ajouter un controleur ou reutiliser `ScreenController` :

```php
$page = new Page('/ma-page');
$page->title('Ma page');
echo $page->render();
```

3. Declarer la route :

```php
$router->get('/ma-page', [self::class, 'maPage']);
```

4. Si vous ajoutez une nouvelle classe, executer `composer dump-autoload` pour mettre a jour l autoload PSR-4.

## Helpers utilitaires

- `load_template('/screens/index', ['name' => 'MyFramework'])`
- `print_template(...)`
- `merge_classes('nav-link', $isActive ? 'active' : '')`
- `dd($variable)` pour debugger et interrompre le script.

## Bonnes pratiques

- Respecter la structure PSR-4 (`namespace MyFramework\...` pour les classes dans `src/`).
- Se servir de `Page::addHeadRenderer()` et `addBottomRenderer()` pour declarer les assets, plutot que d inserer des balises `<script>` directement dans les templates.
- Utiliser `MyFramework::isDevelopmentMode()` pour isoler le code specifique a l environnement.
- Eviter de modifier `vendor/` : toutes les classes du coeur vivent dans `src/`.
- Ajouter `data-use-framework` aux liens internes qui doivent rester dans la navigation AJAX, mais retirer l attribut pour les interactions critiques qui exigent un rechargement complet (telechargements, formulaires sensibles).

## Ressources complementaires

- `README.md` : rappel rapide des objectifs et du fast refresh.
- `templates/screens/index.php` : page vitrine presentant l architecture.
- `templates/screens/profile.php` : exemple d ecran AJAX.
- `docker/` : configuration Apache personalisee (`docker/apache/000-default.conf`).
