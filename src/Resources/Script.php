<?php

namespace MyFramework\Resources;

use MyFramework\Renderer\Renderer;
use MyFramework\Template\Template;

class Script implements Renderer
{

    private ?string $type;
    private string $src;
    private bool $defer;
    private bool $async;
    private ?string $content;
    private ?string $integrity;
    private ?string $cross_origin;

    /**
     * @param string $src
     * @param ?string $type
     * @param bool $defer
     * @param bool $async
     * @param string|null $content
     * @param string|null $integrity
     * @param string|null $cross_origin
     */
    public function __construct(?string $type = null,
        bool $defer = false,
        bool $async = false,
        string $src = '',
        ?string $content = null,
        ?string $integrity = null, ?string $cross_origin = null)
    {
        $this->type = $type;
        $this->src = $src;
        $this->defer = $defer;
        $this->async = $async;
        $this->content = $content;
        $this->integrity = $integrity;
        $this->cross_origin = $cross_origin;
    }

    public function type(string $type): Script
    {
        $this->type = $type;
        return $this;
    }

    public function src(string $src): Script
    {
        $this->src = $src;
        return $this;
    }

    public function defer(bool $defer = true): Script
    {
        $this->defer = $defer;
        return $this;
    }

    public function async(bool $async = true): Script
    {
        $this->async = $async;
        return $this;
    }

    public function content(string $content): Script
    {
        $this->content = $content;
        return $this;
    }

    public function integrity(?string $integrity): Script
    {
        $this->integrity = $integrity;
        return $this;
    }

    public function crossOrigin(?string $cross_origin): Script
    {
        $this->cross_origin = $cross_origin;
        return $this;
    }

    public function render(): string
    {
        return Template::loadTemplate('/partials/resources/script', [
            'type' => $this->type,
            'src' => $this->src,
            'defer' => $this->defer,
            'async' => $this->async,
            'content' => $this->content,
            'integrity' => $this->integrity,
            'cross_origin' => $this->cross_origin,
        ]);
    }

    public static function make(): Script
    {
        return new Script();
    }
}