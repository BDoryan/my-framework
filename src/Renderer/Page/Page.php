<?php

namespace MyFramework\Renderer\Page;

use MyFramework\Renderer\Renderer;
use MyFramework\Renderer\Screen\Screen;
use MyFramework\Template\Template;

class Page extends Screen implements Renderer
{

    private string $lang;
    private string $title = 'Untitled';
    private string $description = 'No description available.';
    private array $keywords = [];
    private string $author = 'Unknown';
    private string $content = '';

    /** @var Renderer[] $head_renders */
    private array $head_renders = [];

    public function __construct(string $path, string $lang = 'en', string $title = 'Untitled', string $description = 'No description available.', array $keywords = [], string $author = 'Unknown', string $content = '')
    {
        parent::__construct($path);
        $this->lang = $lang;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->author = $author;
        $this->content = $content;
    }

    public function addHeadRenderer(Renderer $renderer): self
    {
        $this->head_renders[] = $renderer;
        return $this;
    }

    public function addHeadRenderers(array $renderers): self
    {
        foreach ($renderers as $renderer) {
            $this->addHeadRenderer($renderer);
        }
        return $this;
    }

    public function render(): string
    {
        return Template::loadTemplate('page', [
            'lang' => $this->lang,
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'author' => $this->author,
            'content' => $this->content,
            'top_body' => $this->getTopBody(),
            'bottom_body' => $this->getBottomBody(),
        ]);
    }
}