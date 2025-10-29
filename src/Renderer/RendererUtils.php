<?php

namespace MyFramework\Renderer;

class RendererUtils
{

    /**
     * Render an array of Renderer objects into a single string.
     *
     * @param Renderer[] $renderers
     * @return string
     */
    public static function renders(array $renderers): string
    {
        return implode(PHP_EOL, array_map(function (Renderer $renderer) {
            return $renderer->render();
        }, $renderers));
    }
}