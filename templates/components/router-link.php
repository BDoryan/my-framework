<?php

use MyFramework\Component\Component;

$href = $href ?? '#';
$class = $class ?? '';
$children = $children ?? '';
?>
<a data-use-framework class="<?= trim($class) ?>"
   href="<?= $href ?>">
    <?= $children ?>
</a>
<?php
    return Component::make(__FILE__, 'server-side', [
        'href' => $href,
        'class' => $class,
        'children' => $children,
    ]);
?>