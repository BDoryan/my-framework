<?php

namespace MyFramework\Renderer\Screen;

use MyFramework\Renderer\Renderer;
use MyFramework\Renderer\RendererUtils;
use MyFramework\Template\Template;

class Screen implements Renderer
{

    private string $path;

    public function __construct(string $screen_path)
    {
        $this->path = '/screens/' . preg_replace('#^/screens/#', '', $screen_path);
    }

    /**
     * @var Renderer[] $top_renderers Scripts to be included at the top of the body
     */
    private array $top_renderers = [];
    private array $bottom_renderers = [];

    public function addTopRenderer(Renderer $renderer): self
    {
        $this->top_renderers[] = $renderer;
        return $this;
    }

    public function addBottomRenderer(Renderer $renderer): self
    {
        $this->bottom_renderers[] = $renderer;
        return $this;
    }

    public function addBottomRenderers(array $renderers): self
    {
        foreach ($renderers as $renderer) {
            $this->addBottomRenderer($renderer);
        }
        return $this;
    }

    public function addTopRenderers(array $renderers): self
    {
        foreach ($renderers as $renderer) {
            $this->addTopRenderer($renderer);
        }
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function toTopBody(): string {
        return RendererUtils::renders($this->top_renderers);
    }

    public function toBottomBody(): string {
        return RendererUtils::renders($this->bottom_renderers);
    }

    public function render(array $data = []): string
    {
        return render_components(Template::loadTemplate('/partials/body', [
            'top_body' => $this->toTopBody(),
            'content' => Template::loadTemplate($this->path),
            'bottom_body' => $this->toBottomBody(),
        ]));
    }

    public static function create(string $path): self
    {
        return new self($path);
    }
}