<?php
declare(strict_types=1);

// Define default values for optional variables
use MyFramework\Renderer\Renderer;
use MyFramework\Renderer\RendererUtils;
use MyFramework\Resources\Link;
use MyFramework\Resources\Script;

$head_content = $head_content ?? '';
$top_body = $top_body ?? '';
$bottom_body = $bottom_body ?? '';

/**
 * @var Script[] $scripts_head
 * @var Script[] $scripts_top_body
 * @var Script[] $scripts_bottom_body
 * @var Link[] $links_bottom_body
 * @var Link[] $links_top_body
 * @var Link[] $links_head
 */
$head_content .= RendererUtils::renders($scripts_head ?? []);
$head_content .= RendererUtils::renders($links_head ?? []);

$top_body .= RendererUtils::renders($links_top_body ?? []);
$top_body .= RendererUtils::renders($scripts_top_body ?? []);

$bottom_body .= RendererUtils::renders($links_bottom_body ?? []);
$bottom_body .= RendererUtils::renders($scripts_bottom_body ?? []);

// Get page metadata
$page_metadata = load_template('/partials/page/metadata', [
    'title' => $title ?? 'Untitled',
    'description' => $description ?? 'No description available.',
    'keywords' => $keywords ?? [],
    'author' => $author ?? 'Unknown',
]);

$head_content = $page_metadata . $head_content;

$head = load_template('/partials/head', [
    'content' => $head_content,
]);

$body = load_template('/partials/body', [
    'top_body' => $top_body ?? '',
    'content' => $content ?? '',
    'bottom_body' => $bottom_body ?? '',
]);

print_template('dom', [
    'head' => $head,
    'body' => $body,
    'lang' => $lang ?? 'en',
]);