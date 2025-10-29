<?php

namespace MyFramework\Resources;

use MyFramework\Renderer\Renderer;
use MyFramework\Template\Template;

class Link implements Renderer
{

    private string $href;
    private string $rel;
    private ?string $type;
    private ?string $media;
    private ?string $integrity;
    private ?string $cross_origin;

    public function __construct(
        string  $href,
        string  $rel = 'stylesheet',
        ?string $type = null,
        ?string $media = null,
        ?string $integrity = null,
        ?string $cross_origin = null
    )
    {
        $this->href = $href;
        $this->rel = $rel;
        $this->type = $type;
        $this->media = $media;
        $this->integrity = $integrity;
        $this->cross_origin = $cross_origin;
    }

    public function href(string $href): Link
    {
        $this->href = $href;
        return $this;
    }

    public function rel(string $rel): Link
    {
        $this->rel = $rel;
        return $this;
    }

    public function type(?string $type): Link
    {
        $this->type = $type;
        return $this;
    }

    public function media(?string $media): Link
    {
        $this->media = $media;
        return $this;
    }

    public function integrity(?string $integrity): Link
    {
        $this->integrity = $integrity;
        return $this;
    }

    public function crossOrigin(?string $cross_origin): Link
    {
        $this->cross_origin = $cross_origin;
        return $this;
    }

    public function render(): string
    {
        return Template::loadTemplate('/partials/resources/link', [
            'href' => $this->href,
            'rel' => $this->rel,
            'type' => $this->type,
            'media' => $this->media,
            'integrity' => $this->integrity,
            'cross_origin' => $this->cross_origin,
        ]);
    }

    public static function make(): Link
    {
        return new Link('');
    }
}