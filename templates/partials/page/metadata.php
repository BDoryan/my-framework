<title><?= $title ?? 'Untitled' ?></title>
<meta name="description" content="<?= $description ?? 'No description available.' ?>">
<meta name="keywords" content="<?= isset($keywords) ? implode(', ', $keywords) : '' ?>">
<meta name="author" content="<?= $author ?? 'Unknown' ?>">