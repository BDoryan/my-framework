<?php

use MyFramework\Component\Component;
use MyFramework\Component\ComponentRenderer;
use MyFramework\Template\Template;

/**
 * Load a template with given variables.
 *
 * @param string $template_path
 * @param array $variables
 * @return string
 */
function load_template(string $template_path, array $variables = []): string
{
    return Template::loadTemplate($template_path, $variables);
}

/**
 * Print a template with given variables.
 *
 * @param string $template_path
 * @param array $variables
 * @return void
 */
function print_template(string $template_path, array $variables = []): void
{
    Template::printTemplate($template_path, $variables);
}

/**
 * Print debug information in a readable format.
 *
 * @param mixed $data
 * @return void
 */
function debug_print($data): void
{
    echo '<pre style="background-color: #f4f4f4; border: 1px solid #ccc; padding: 10px;">';
    print_r($data);
    echo '</pre>';
}

/**
 * Merge multiple CSS class names into a single string.
 *
 * @param string ...$classes
 * @return string
 */
function merge_classes(...$classes): string
{
    $filtered_classes = array_filter($classes, fn($class) => !empty($class));
    return trim(implode(' ', $filtered_classes));
}

/**
 * Print debug information and terminate the script.
 *
 * @param mixed $data
 * @return void
 */
function dd($data): void
{
    debug_print($data);
    die();
}

/**
 * This method transform array data to json string for attribute html value
 *
 * @param array $data
 * @return string
 */
function json_to_html_attribute_value(array $data): string
{
    return htmlspecialchars(json_encode($data), ENT_QUOTES);
}

/**
 * Render components within the given HTML string.
 *
 * @param string $html
 * @param int $depth
 * @return string
 */
function render_components(string $html, int $depth = 0): string
{
    return ComponentRenderer::renderComponents($html, $depth);
}

// TODO: Temporary function for backward compatibility
/*
function render_components($html, $depth = 0)
{
    static $loaded_components = [];
    static $collected_scripts = [];
    static $collected_styles = [];

    if ($depth > 20) {
        return $html;
    }

    $pattern = '/<([a-z0-9-]+)([^>]*)>(.*?)<\/\1>/is';

    // Use preg_replace_callback to process each component tag
    $html = preg_replace_callback($pattern, function ($matches) use (&$loaded_components, &$collected_scripts, &$collected_styles, $depth) {
        $tag = $matches[1];

        // Get attributes and inner content
        $attributes = $matches[2];

        // Trim inner content to avoid unnecessary spaces
        $innerContent = $matches[3];

        // If component file does not exist, return original HTML
        if(!Component::hasComponent($tag)) {
            $innerContent = render_components($innerContent, $depth + 1);
            return "<{$tag}{$attributes}>{$innerContent}</{$tag}>";
        }

        // Load the component
        $component = Component::getComponentByTag($tag);

        // Convert attributes to props
        preg_match_all('/(\w+)="([^"]*)"/', $attributes, $attrMatches, PREG_SET_ORDER);
        $props = [];
        foreach ($attrMatches as $attr) {
            $props[$attr[1]] = $attr[2];
        }
        $props['children'] = trim($innerContent);

        // Render the component
        $rendered = $component->render($props);

        // Extract scripts and styles
        preg_match_all('/<script\b[^>]*>[\s\S]*?<\/script>/i', $rendered, $scripts);
        preg_match_all('/<style\b[^>]*>[\s\S]*?<\/style>/i', $rendered, $styles);

        // Clean rendered content
        $rendered_clean = trim(
            preg_replace(['/<script\b[^>]*>[\s\S]*?<\/script>/i', '/<style\b[^>]*>[\s\S]*?<\/style>/i'], '', $rendered)
        );

        // Store the scripts and styles only once
        if (!in_array($tag, $loaded_components)) {
            $loaded_components[] = $tag;
            if (!empty($scripts[0])) $collected_scripts[$tag] = implode("\n", $scripts[0]);
            if (!empty($styles[0])) $collected_styles[$tag] = implode("\n", $styles[0]);
        }

        // Return only the inner content for server-side components
        if ($component->isServerSide())
            return $rendered_clean;

        // For client-side components, return the full tag with attributes (scripts and styles will be appended later)
        return "<{$tag}{$attributes}>{$rendered_clean}</{$tag}>";
    }, $html);

    // At the top level, append collected scripts and styles
    if ($depth === 0) {
        $assets = implode("\n", array_values($collected_styles))
            . "\n"
            . implode("\n", array_values($collected_scripts));
        $html .= "\n" . $assets;
    }

    return $html;
}*/

