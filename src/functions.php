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

/**
 * Print debug information in a readable format.
 *
 * @param mixed $data
 * @return void
 */
function debug_print($data): void {
    echo '<pre style="background-color: #f4f4f4; border: 1px solid #ccc; padding: 10px;">';
    print_r($data);
    echo '</pre>';
}

/**
 * Print debug information and terminate the script.
 *
 * @param mixed $data
 * @return void
 */
function dd($data): void {
    debug_print($data);
    die();
}