<?php

namespace MyFramework\Renderer;

class RendererUtils
{

    /**
     * Render an array of Renderer objects into a single string.
     *
     * @param Renderer[] $renderers
     * @param array $data Optional data to pass to each renderer
     * @return string
     */
    public static function renders(array $renderers, array $data = []): string
    {
        return implode(PHP_EOL, array_map(function (Renderer $renderer) use ($data) {
            return $renderer->render($data);
        }, $renderers));
    }
}