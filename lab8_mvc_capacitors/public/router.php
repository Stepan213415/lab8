<?php
// PHP built-in server: serve only styles/images; route all other URLs through MVC.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$publicRoot = realpath(__DIR__);
$file = realpath(__DIR__ . $path);
$isStaticPath = str_starts_with($path, '/assets/') || str_starts_with($path, '/uploads/');
$isAllowedExtension = (bool) preg_match('/\.(css|js|png|jpg|jpeg|webp|gif|ico)$/i', $path);
if ($isStaticPath && $isAllowedExtension && $file &&
    str_starts_with($file, $publicRoot . DIRECTORY_SEPARATOR) && is_file($file)) {
    return false;
}
require __DIR__ . '/index.php';
