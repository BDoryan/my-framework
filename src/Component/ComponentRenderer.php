<?php

namespace MyFramework\Component;

class ComponentRenderer
{

    const PATTERN_COMPONENT_TAG = '/<([a-z0-9-]+)([^>]*)>(.*?)<\/\1>/is';
    const PATTERN_ATTRIBUTES = '/(\w+)="([^"]*)"/';

    const PATTERN_SCRIPT_TAG = '/<script\b[^>]*>[\s\S]*?<\/script>/i';
    const PATTERN_STYLE_TAG = '/<style\b[^>]*>[\s\S]*?<\/style>/i';
    const MAX_DEPTH = 20;

    static array $loaded_components = [];
    static array $collected_scripts = [];
    static array $collected_styles = [];

    /**
     * Render components within the given HTML string.
     * This function processes custom component tags in the HTML,
     * renders them, and collects any associated scripts and styles.
     *
     * @param string $html
     * @param int $depth
     * @return string
     */
    public static function renderComponents(string $html, int $depth = 0): string
    {
        if ($depth > self::MAX_DEPTH)
            return $html;

        // Use preg_replace_callback to process each component tag
        $html = preg_replace_callback(self::PATTERN_COMPONENT_TAG, function ($matches) use ($depth) {
            $tag = $matches[1];

            // Get attributes and inner content
            $attributes = $matches[2];

            // Trim inner content to avoid unnecessary spaces
            $innerContent = $matches[3];

            // If component file does not exist, return original HTML
            if (!Component::hasComponent($tag)) {
                $innerContent = self::renderComponents($innerContent, $depth + 1);
                return "<{$tag}{$attributes}>{$innerContent}</{$tag}>";
            }

            // Load the component
            $component = Component::getComponentByTag($tag);

            // Convert attributes to props
            preg_match_all(self::PATTERN_ATTRIBUTES, $attributes, $attrMatches, PREG_SET_ORDER);
            $props = [];
            foreach ($attrMatches as $attr) {
                $props[$attr[1]] = $attr[2];
            }
            $props['children'] = trim($innerContent);

            // Render the component
            $rendered = $component->render($props);

            // Extract scripts and styles
            preg_match_all(self::PATTERN_SCRIPT_TAG, $rendered, $scripts);
            preg_match_all(self::PATTERN_STYLE_TAG, $rendered, $styles);

            // Clean rendered content
            $rendered_clean = trim(
                preg_replace([self::PATTERN_SCRIPT_TAG, self::PATTERN_STYLE_TAG], '', $rendered)
            );

            // Store the scripts and styles only once
            if (!in_array($tag, self::$loaded_components)) {
                $loaded_components[] = $tag;
                if (!empty($scripts[0])) self::$collected_scripts[$tag] = implode("\n", $scripts[0]);
                if (!empty($styles[0])) self::$collected_styles[$tag] = implode("\n", $styles[0]);
            }

            // Return only the inner content for server-side components
            if ($component->isServerSide())
                return $rendered_clean;

            // For client-side components, return the full tag with attributes (scripts and styles will be appended later)
            return "<{$tag}{$attributes}>{$rendered_clean}</{$tag}>";
        }, $html);

        // At the top level, append collected scripts and styles
        if ($depth === 0) {
            $assets = implode("\n", array_values(self::$collected_styles))
                . "\n"
                . implode("\n", array_values(self::$collected_scripts));
            $html .= "\n" . $assets;
        }

        return $html;
    }

}