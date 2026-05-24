<?php

$cssFiles = glob(__DIR__ . '/public/css/*.css');
foreach ($cssFiles as $file) {
    $content = file_get_contents($file);
    $content = str_replace("'DM Sans'", "'Plus Jakarta Sans'", $content);
    $content = str_replace("'Syne'", "'Satoshi'", $content);
    $content = str_replace("'Inter'", "'Plus Jakarta Sans'", $content);
    file_put_contents($file, $content);
}

$viewFiles = array_merge(
    glob(__DIR__ . '/resources/views/*.blade.php'),
    glob(__DIR__ . '/resources/views/*/*.blade.php')
);

$newFonts = '<link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,400&display=swap" rel="stylesheet">' . "\n    " . '<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">';

foreach ($viewFiles as $file) {
    $content = file_get_contents($file);
    $content = preg_replace('/<link[^>]*fonts\.googleapis\.com[^>]*>/i', $newFonts, $content);
    file_put_contents($file, $content);
}

echo "Done\n";
