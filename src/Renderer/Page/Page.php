<?php

namespace MyFramework\Renderer\Page;

use MyFramework\MyFramework;
use MyFramework\Renderer\Renderer;
use MyFramework\Renderer\RendererUtils;
use MyFramework\Renderer\Screen\Screen;
use MyFramework\Resources\Script;
use MyFramework\Template\Template;

class Page extends Screen implements Renderer
{

    private string $lang;
    private string $title = 'Untitled';
    private string $description = 'No description available.';
    private array $keywords = [];
    private string $author = 'Unknown';
    private string $content = '';

    /** @var Renderer[] $head_renderers */
    private array $head_renderers = [];

    public function __construct(string $screen_path, string $lang = 'en', string $title = 'Untitled', string $description = 'No description available.', array $keywords = [], string $author = 'Unknown', string $content = '')
    {
        parent::__construct($screen_path);
        $this->lang = $lang;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->author = $author;
        $this->content = $content;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function keywords(array $keywords): self
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function author(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function addHeadRenderer(Renderer $renderer): self
    {
        $this->head_renderers[] = $renderer;
        return $this;
    }

    public function addHeadRenderers(array $renderers): self
    {
        foreach ($renderers as $renderer) {
            $this->addHeadRenderer($renderer);
        }
        return $this;
    }

    public function toTopBody(): string
    {
        $header = Template::loadTemplate('/partials/header');
        return parent::toTopBody() . $header;
    }

    public function toBottomBody(): string
    {
        $footer = Template::loadTemplate('/partials/footer');
        return $footer . parent::toBottomBody();
    }

    public function render(array $data = []): string
    {
        // Add default framework scripts
        $this->addHeadRenderer(Script::make()
            ->src('/resources/js/framework.js')
            ->defer()
            ->type('module'));

        // Add development tools script in development mode
        if (MyFramework::isDevelopmentMode()) {
            $this->addHeadRenderer(Script::make()
                ->src('/resources/js/__dev/dev-tools.js')
                ->type('module'));
        }

        // Combine header, content, and footer
        $content = parent::render();

        if (($_GET['_ajax'] ?? '') == 1) {
            return $content;
        }

        // Load and render the template
        return Template::loadTemplate('page', [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'author' => $this->author,
            'lang' => $this->lang,

            'head_content' => RendererUtils::renders($this->head_renderers),

            'content' => $content
        ]);
    }
}