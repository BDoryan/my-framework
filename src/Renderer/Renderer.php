<?php

namespace MyFramework\Renderer;

interface Renderer
{

    /**
     * Render the HTML representation of the resource.
     *
     * @param array $data Additional data to pass to the renderer.
     * @return string
     */
    public function render(array $data = []): string;

}