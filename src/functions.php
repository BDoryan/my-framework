<?php

use MyFramework\Template\Template;

/**
 * Load a template with given variables.
 *
 * @param string $template_path
 * @param array $variables
 * @return string
 */
function load_template(string $template_path, array $variables = []): string {
    return Template::loadTemplate($template_path, $variables);
}

/**
 * Print a template with given variables.
 *
 * @param string $template_path
 * @param array $variables
 * @return void
 */
function print_template(string $template_path, array $variables = []): void {
    Template::printTemplate($template_path, $variables);
}