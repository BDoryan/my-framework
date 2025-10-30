<style>
    .framework-page {
        background: #f9fafc;
        color: #1c1f3b;
        font-family: "Inter", "Segoe UI", sans-serif;
        line-height: 1.6;
    }

    .framework-page a {
        color: inherit;
        text-decoration: none;
    }

    .fw-hero {
        position: relative;
        padding: 6rem 0 5rem;
        background: linear-gradient(140deg, #fff 0%, #ffeaf0 40%, #f4f6ff 100%);
        overflow: hidden;
    }

    .fw-hero::after {
        content: "";
        position: absolute;
        inset: 12% 6% auto auto;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(255, 94, 87, 0.35) 0%, rgba(255, 94, 87, 0) 70%);
        opacity: 0.9;
        pointer-events: none;
    }

    .fw-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        background: rgba(255, 94, 87, 0.12);
        border: 1px solid rgba(255, 94, 87, 0.25);
        color: #f9322c;
        font-size: 0.8rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 600;
    }

    .fw-hero-title {
        font-size: clamp(2.8rem, 4vw, 3.6rem);
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 1.5rem 0 1rem;
        color: #1a1d34;
    }

    .fw-hero-lead {
        font-size: 1.2rem;
        max-width: 40rem;
        color: #404461;
        margin-bottom: 2.5rem;
    }

    .fw-cta-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .fw-btn-primary,
    .fw-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.85rem 2.6rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.03em;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .fw-btn-primary {
        background: linear-gradient(135deg, #ff5e57, #f9322c);
        color: #fff;
        box-shadow: 0 12px 30px rgba(249, 50, 44, 0.2);
    }

    .fw-btn-secondary {
        background: #fff;
        color: #f9322c;
        border: 1px solid rgba(249, 50, 44, 0.2);
    }

    .fw-btn-primary:hover,
    .fw-btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 32px rgba(249, 50, 44, 0.24);
    }

    .fw-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        color: #1a1d34;
    }

    .fw-stat-card {
        min-width: 160px;
    }

    .fw-stat-value {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        color: #f9322c;
    }

    .fw-stat-label {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        color: #6b6f8b;
    }

    .fw-image-card {
        position: relative;
        background: #fff;
        border-radius: 1.5rem;
        padding: 2.5rem;
        box-shadow: 0 24px 80px rgba(26, 29, 52, 0.08);
        border: 1px solid rgba(26, 29, 52, 0.06);
    }

    .fw-image-card h3 {
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #f9322c;
        margin-bottom: 1.5rem;
    }

    .fw-image-card p {
        color: #555a7b;
        margin-bottom: 1rem;
    }

    .fw-section {
        padding: 5rem 0;
    }

    .fw-section-header {
        max-width: 780px;
        margin: 0 auto 3rem;
        text-align: center;
    }

    .fw-section-label {
        display: inline-block;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        background: rgba(249, 50, 44, 0.08);
        color: #f9322c;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .fw-section-title {
        font-size: clamp(2rem, 3vw, 2.8rem);
        margin-bottom: 1rem;
        color: #1a1d34;
    }

    .fw-section-subtitle {
        color: #50557a;
        margin: 0 auto;
    }

    .fw-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.8rem;
    }

    .fw-feature-card {
        background: #fff;
        border: 1px solid rgba(26, 29, 52, 0.06);
        border-radius: 1.2rem;
        padding: 2rem;
        box-shadow: 0 18px 56px rgba(26, 29, 52, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .fw-feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 28px 80px rgba(26, 29, 52, 0.12);
    }

    .fw-feature-card h3 {
        font-size: 1.2rem;
        margin-bottom: 0.75rem;
        color: #1c1f3b;
    }

    .fw-feature-card p {
        color: #525772;
    }

    .fw-developer-experience {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2.4rem;
        align-items: start;
    }

    .fw-checklist {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .fw-checklist li {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(26, 29, 52, 0.08);
    }

    .fw-checklist li:last-child {
        border-bottom: none;
    }

    .fw-check {
        color: #f9322c;
        font-weight: 700;
    }

    .fw-code-sample {
        background: #1b1f36;
        border-radius: 1.2rem;
        padding: 2rem;
        color: #f6f8ff;
        box-shadow: 0 24px 70px rgba(26, 29, 52, 0.35);
    }

    .fw-code-sample pre {
        margin: 0;
        font-family: "Fira Code", "SFMono-Regular", monospace;
        font-size: 0.95rem;
        white-space: pre-wrap;
    }

    .fw-architecture {
        background: linear-gradient(145deg, #ffffff 0%, #f2f5ff 100%);
        border-radius: 1.6rem;
        padding: 3rem;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 18px 60px rgba(26, 29, 52, 0.12);
    }

    .fw-steps {
        display: grid;
        gap: 2rem;
    }

    .fw-step {
        display: grid;
        gap: 0.5rem;
    }

    .fw-step strong {
        font-size: 1.1rem;
        color: #f9322c;
    }

    .fw-step span {
        color: #3c4166;
    }

    .fw-ecosystem-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 2rem;
    }

    .fw-ecosystem-card {
        background: #fff;
        border-radius: 1.2rem;
        padding: 2rem;
        border: 1px solid rgba(26, 29, 52, 0.06);
        box-shadow: 0 16px 52px rgba(26, 29, 52, 0.08);
    }

    .fw-ecosystem-card h3 {
        margin-bottom: 0.8rem;
        color: #1a1d34;
    }

    .fw-ecosystem-card p {
        color: #565a77;
        margin-bottom: 1rem;
    }

    .fw-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .fw-pill {
        padding: 0.45rem 1.1rem;
        border-radius: 999px;
        background: rgba(249, 50, 44, 0.08);
        color: #f9322c;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .fw-community {
        background: #fff;
        border-radius: 1.5rem;
        padding: 3rem;
        border: 1px solid rgba(26, 29, 52, 0.06);
        box-shadow: 0 22px 70px rgba(26, 29, 52, 0.12);
    }

    .fw-community-columns {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2.4rem;
    }

    .fw-community h3 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        color: #1a1d34;
    }

    .fw-cta {
        background: linear-gradient(140deg, #ff5e57 0%, #f9322c 55%, #f45f4d 100%);
        border-radius: 1.6rem;
        padding: 3.2rem;
        color: #fff;
        text-align: center;
        box-shadow: 0 32px 80px rgba(249, 50, 44, 0.38);
    }

    .fw-cta h2 {
        font-size: clamp(2.1rem, 3.5vw, 2.8rem);
        margin-bottom: 1rem;
    }

    .fw-cta p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
    }

    @media (max-width: 991.98px) {
        .fw-hero {
            padding: 5rem 0 4rem;
        }

        .fw-image-card {
            margin-top: 3rem;
        }
    }

    @media (max-width: 767.98px) {
        .fw-hero::after {
            width: 260px;
            height: 260px;
            inset: 8% -10% auto auto;
        }

        .fw-hero-title {
            font-size: 2.4rem;
        }

        .fw-cta-group {
            flex-direction: column;
            align-items: stretch;
        }

        .fw-code-sample pre {
            font-size: 0.9rem;
        }
    }
</style>

<div class="framework-page">
    <section class="fw-hero">
        <div class="container">
            <span class="fw-badge">Micro-framework PHP</span>
            <h1 class="fw-hero-title">MyFramework, un terrain d'expérimentation moderne</h1>
            <p class="fw-hero-lead">
                MyFramework assemble un routeur minimaliste, un moteur de templates et un pipeline de ressources pour créer des écrans dynamiques sans dépendances lourdes. Le projet sert de laboratoire pour explorer comment structurer un framework maison.
            </p>
            <div class="fw-cta-group">
                <a class="fw-btn-primary" href="#features">Voir les briques</a>
                <a class="fw-btn-secondary" href="#experience">Côté développeur</a>
            </div>
            <div class="fw-stats">
                <div class="fw-stat-card">
                    <span class="fw-stat-value">Router</span>
                    <span class="fw-stat-label">GET, POST, routes nommées et placeholders</span>
                </div>
                <div class="fw-stat-card">
                    <span class="fw-stat-value">Templates</span>
                    <span class="fw-stat-label">Page, Screen et partials PHP réutilisables</span>
                </div>
                <div class="fw-stat-card">
                    <span class="fw-stat-value">Dev mode</span>
                    <span class="fw-stat-label">Fast refresh + navigation AJAX native</span>
                </div>
            </div>
            <div class="fw-image-card" style="margin-top: 3.2rem;">
                <h3>Pipeline de rendu</h3>
                <p>
                    Les classes <code>Page</code> et <code>Screen</code> orchestrent le chargement des templates et des partials.
                    <code>Template::loadTemplate()</code> garde le rendu simple, tout en permettant d'injecter des données côté serveur.
                </p>
                <p>
                    Les assets sont déclarés via <code>Resources\Link</code> et <code>Resources\Script</code>, ce qui rend explicites les dépendances de chaque écran et centralise la gestion des balises <code>&lt;head&gt;</code> et <code>&lt;body&gt;</code>.
                </p>
            </div>
        </div>
    </section>

    <section class="fw-section" id="features">
        <div class="container">
            <div class="fw-section-header">
                <span class="fw-section-label">Fonctionnalités</span>
                <h2 class="fw-section-title">Les briques prêtes à l'emploi</h2>
                <p class="fw-section-subtitle">
                    Chaque élément du noyau est écrit en PHP natif. Vous pouvez lire, modifier et étendre le code sans magie cachée.
                </p>
            </div>
            <div class="fw-features-grid">
                <div class="fw-feature-card">
                    <h3>Routeur expressif</h3>
                    <p>
                        <code>MyFramework\Router\Router</code> gère les verbes HTTP, les placeholders nommés et les handlers sous forme de closures, de notation <code>Controller@method</code> ou de tableaux.
                        Les routes peuvent être nommées pour générer des URLs fiables.
                    </p>
                </div>
                <div class="fw-feature-card">
                    <h3>Screens composables</h3>
                    <p>
                        Les classes <code>Screen</code> et <code>Page</code> encapsulent le rendu d'un écran complet, en combinant partials (<code>header</code>, <code>body</code>, <code>footer</code>) et contenus spécifiques à chaque route.
                    </p>
                </div>
                <div class="fw-feature-card">
                    <h3>Gestion déclarative des assets</h3>
                    <p>
                        Les ressources front passent par <code>Resources\Link</code> et <code>Resources\Script</code>. Chaque page déclare ses scripts et styles, qui sont ensuite rendus proprement dans le layout global.
                    </p>
                </div>
                <div class="fw-feature-card">
                    <h3>Journalisation intégrée</h3>
                    <p>
                        <code>Logger\Logger</code> crée automatiquement un fichier <code>app.log</code>, gère les permissions et colore les niveaux en CLI. Aucun setup supplémentaire n'est requis pour tracer ce qu'il se passe.
                    </p>
                </div>
                <div class="fw-feature-card">
                    <h3>Helpers pratiques</h3>
                    <p>
                        Des fonctions comme <code>load_template()</code>, <code>merge_classes()</code> ou <code>dd()</code> simplifient l'écriture des vues et le debug sans introduire de dépendance externe.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="fw-section" id="experience">
        <div class="container">
            <div class="fw-section-header">
                <span class="fw-section-label">Expérience développeur</span>
                <h2 class="fw-section-title">Un terrain de jeu confortable</h2>
                <p class="fw-section-subtitle">
                    Le framework est pensé pour itérer rapidement, observer les effets de vos changements et comprendre les pièces qui composent un outil maison.
                </p>
            </div>
            <div class="fw-developer-experience">
                <div>
                    <ul class="fw-checklist">
                        <li>
                            <span class="fw-check">✓</span>
                            <div>
                                <strong>Fast refresh intégré</strong>
                                <p>
                                    Le script <code>public/__dev/fast-refresh.php</code> surveille <code>src/</code>, <code>templates/</code> et <code>public/resources/</code>.
                                    Activez <code>MY_FRAMEWORK_ENVIRONMENT=development</code> pour recharger automatiquement vos écrans.
                                </p>
                            </div>
                        </li>
                        <li>
                            <span class="fw-check">✓</span>
                            <div>
                                <strong>Navigation progressive</strong>
                                <p>
                                    <code>public/resources/js/framework.js</code> intercepte les liens annotés <code>data-use-framework</code>, charge les écrans via AJAX et met à jour l'historique du navigateur.
                                </p>
                            </div>
                        </li>
                        <li>
                            <span class="fw-check">✓</span>
                            <div>
                                <strong>Environnements clairs</strong>
                                <p>
                                    <code>MyFramework::isDevelopmentMode()</code> et <code>isProductionMode()</code> exposent l'état courant. Ils pilotent par exemple l'injection des scripts de dev dans <code>Page::render()</code>.
                                </p>
                            </div>
                        </li>
                        <li>
                            <span class="fw-check">✓</span>
                            <div>
                                <strong>Stack reproductible</strong>
                                <p>
                                    <code>Dockerfile</code> et <code>docker-compose.yml</code> offrent une base pour lancer le framework avec PHP 8.3, ou vous pouvez utiliser <code>php -S 127.0.0.1:8000 -t public</code> pour un serveur rapide.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="fw-code-sample">
                    <pre><code>&lt;?php

use MyFramework\Renderer\Page\Page;
use MyFramework\Resources\Link;
use MyFramework\Resources\Script;

// src/Controllers/ScreenController.php
$page = new Page('/index');
$page->title('Welcome to MyFramework')
    ->description('This is the homepage of MyFramework.');

$page->addHeadRenderer(
    Link::make()
        ->href('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css')
        ->rel('stylesheet')
        ->crossOrigin('anonymous')
        ->integrity('sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB')
);

$page->addBottomRenderer(
    Script::make()
        ->src('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js')
        ->integrity('sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI')
        ->crossOrigin('anonymous')
);

echo $page->render();
</code></pre>
                </div>
            </div>
        </div>
    </section>

    <section class="fw-section" id="architecture">
        <div class="container">
            <div class="fw-section-header">
                <span class="fw-section-label">Architecture</span>
                <h2 class="fw-section-title">Une structure claire en PHP natif</h2>
                <p class="fw-section-subtitle">
                    Chaque dossier a un rôle précis. Vous pouvez explorer le code et comprendre rapidement comment l'assemblage fonctionne.
                </p>
            </div>
            <div class="fw-architecture">
                <div class="fw-steps">
                    <div class="fw-step">
                        <strong>1. Routage & contrôleurs</strong>
                        <span>
                            Les contrôleurs vivent dans <code>src/Controllers</code> et étendent la classe <code>Controller</code>.
                            <code>ScreenController::routes()</code> enregistre les chemins via le routeur central.
                        </span>
                    </div>
                    <div class="fw-step">
                        <strong>2. Rendu des écrans</strong>
                        <span>
                            <code>src/Renderer</code> fournit les abstractions <code>Renderer</code>, <code>Screen</code> et <code>Page</code>.
                            Elles chargent les partials depuis <code>templates/partials</code> et encapsulent les scripts haut/bas de page.
                        </span>
                    </div>
                    <div class="fw-step">
                        <strong>3. Templates organisés</strong>
                        <span>
                            Les écrans se trouvent dans <code>templates/screens</code>. Ils restent de simples fichiers PHP, ce qui rend le templating transparent et compatible avec n'importe quelle librairie CSS.
                        </span>
                    </div>
                    <div class="fw-step">
                        <strong>4. Ressources et dev tools</strong>
                        <span>
                            <code>public/resources/js/framework.js</code> gère l'enrichissement côté client et <code>public/__dev</code> héberge le watcher de fichiers.
                            Les scripts sont servis depuis <code>public/</code>, prêts pour un serveur PHP natif ou Nginx.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fw-section" id="tooling">
        <div class="container">
            <div class="fw-section-header">
                <span class="fw-section-label">Boîte à outils</span>
                <h2 class="fw-section-title">Ressources incluses dans le dépôt</h2>
                <p class="fw-section-subtitle">
                    MyFramework reste compact mais fournit tout ce qu'il faut pour expérimenter : configuration, scripts et exemples prêts à explorer.
                </p>
            </div>
            <div class="fw-ecosystem-grid">
                <div class="fw-ecosystem-card">
                    <h3>Configuration simple</h3>
                    <p>
                        Le fichier <code>.env</code> contient le flag <code>MY_FRAMEWORK_ENVIRONMENT</code>. Passez-le à <code>production</code> pour désactiver les outils de développement.
                    </p>
                    <div class="fw-pills">
                        <span class="fw-pill">.env</span>
                        <span class="fw-pill">Fast refresh</span>
                        <span class="fw-pill">Modes</span>
                    </div>
                </div>
                <div class="fw-ecosystem-card">
                    <h3>Tooling local</h3>
                    <p>
                        Utilisez <code>composer install</code> puis <code>php -S 127.0.0.1:8000 -t public</code>, ou lancez <code>docker compose up</code> pour travailler dans un environnement isolé.
                    </p>
                    <div class="fw-pills">
                        <span class="fw-pill">Composer</span>
                        <span class="fw-pill">PHP 8.3</span>
                        <span class="fw-pill">Docker</span>
                    </div>
                </div>
                <div class="fw-ecosystem-card">
                    <h3>Exemples embarqués</h3>
                    <p>
                        Consultez <code>templates/screens/profile.php</code> pour un écran interactif et <code>templates/partials/header.php</code> pour voir la navigation AJAX en action.
                    </p>
                    <div class="fw-pills">
                        <span class="fw-pill">Bootstrap</span>
                        <span class="fw-pill">AJAX</span>
                        <span class="fw-pill">Partials</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fw-section">
        <div class="container">
            <div class="fw-community" id="resources">
                <div class="fw-community-columns">
                    <div>
                        <span class="fw-section-label">Documentation locale</span>
                        <h3>Plongez dans le code source</h3>
                        <p>
                            Le dépôt est volontairement compact : <code>README.md</code> présente les objectifs du projet et chaque namespace (<code>Router</code>, <code>Template</code>, <code>Renderer</code>) expose des classes strictement typées.
                        </p>
                        <p>
                            Parcourez le code, ouvrez les fichiers et modifiez-les : MyFramework est fait pour comprendre comment les pièces interagissent.
                        </p>
                    </div>
                    <div>
                        <h3>Points d'entrée conseillés</h3>
                        <ul class="fw-checklist">
                            <li>
                                <span class="fw-check">✓</span>
                                <div>
                                    <strong>src/MyFramework.php</strong>
                                    <p>
                                        Initialise le routeur, charge les variables d'environnement et résout un dossier de logs accessible.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <span class="fw-check">✓</span>
                                <div>
                                    <strong>src/functions.php</strong>
                                    <p>
                                        Fournit les helpers globaux utilisés par les templates (<code>load_template()</code>, <code>debug_print()</code>, etc.).
                                    </p>
                                </div>
                            </li>
                            <li>
                                <span class="fw-check">✓</span>
                                <div>
                                    <strong>public/resources/js/framework.js</strong>
                                    <p>
                                        Montre comment enrichir la navigation sans se reposer sur un framework JavaScript complet.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fw-section" style="padding-bottom: 6rem;">
        <div class="container">
            <div class="fw-cta">
                <h2>Prêt à explorer MyFramework ?</h2>
                <p>
                    Clonez le dépôt, exécutez <code>composer install</code>, démarrez <code>php -S 127.0.0.1:8000 -t public</code> ou <code>docker compose up</code>, puis assurez-vous que <code>MY_FRAMEWORK_ENVIRONMENT=development</code> pour profiter du fast refresh.
                </p>
                <div class="fw-cta-group" style="justify-content: center;">
                    <a class="fw-btn-secondary" href="#architecture" style="background: rgba(255,255,255,0.2); color: #fff;">Comprendre l'architecture</a>
                    <a class="fw-btn-primary" href="#experience" style="background: #fff; color: #f9322c; box-shadow: none;">Configurer le mode dev</a>
                </div>
            </div>
        </div>
    </section>
</div>
