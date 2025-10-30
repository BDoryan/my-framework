<?php

use MyFramework\Component\Component;
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

// TODO: Temporary function for backward compatibility
function render_components($html, $depth = 0)
{
    static $loaded_components = [];
    static $collected_scripts = [];
    static $collected_styles = [];

    if ($depth > 20) {
        return $html;
    }

    $pattern = '/<([a-z0-9-]+)([^>]*)>(.*?)<\/\1>/is';
    $has_component = false;

    $html = preg_replace_callback($pattern, function ($matches) use (&$loaded_components, &$collected_scripts, &$collected_styles, &$has_component, $depth) {
        $tag = $matches[1];

        $attributes = $matches[2];
        $innerContent = $matches[3];

        $render_path = __DIR__ . "/../templates/components/{$tag}.php";

        // If component file does not exist, return original HTML
        if (!file_exists($render_path)) {
            $innerContent = render_components($innerContent, $depth + 1);
            return "<{$tag}{$attributes}>{$innerContent}</{$tag}>";
        }

        $has_component = true;

        // Convert attributes to props
        preg_match_all('/(\w+)="([^"]*)"/', $attributes, $attrMatches, PREG_SET_ORDER);
        $props = [];
        foreach ($attrMatches as $attr) {
            $props[$attr[1]] = $attr[2];
        }
        $props['children'] = trim($innerContent);

        // Load the component
        $component = Component::loadComponent($render_path);

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
        return "<{$tag}{$attributes}>{$rendered_clean}</{$tag}>";
    }, $html);

//    if ($has_component) {
//        $html = render_components($html, $depth + 1);
//    }

    // At the top level, append collected scripts and styles
    if ($depth === 0) {
        $assets = implode("\n", array_values($collected_styles))
            . "\n"
            . implode("\n", array_values($collected_scripts));
        $html .= "\n" . $assets;
    }

    return $html;
}

