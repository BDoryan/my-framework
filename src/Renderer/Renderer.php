<?php

namespace MyFramework\Renderer;

interface Renderer
{

    /**
     * Render the HTML representation of the resource.
     *
     * @return string
     */
    public function render(): string;

}