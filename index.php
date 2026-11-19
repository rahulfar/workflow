<?php
/**
 * Front Controller & Root Router - WorkFlow Pro
 * Provides seamless routing when web server document root points to project root or subfolder.
 * Compatible with Apache mod_rewrite, PHP CLI server, Nginx, and mobile IP access.
 */

// If running with PHP CLI built-in web server (e.g. php -S 0.0.0.0:8000), serve static files from public/
if (php_sapi_name() === 'cli-server') {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    $publicFile = __DIR__ . '/public' . $uriPath;
    if ($uriPath !== '/' && $uriPath !== '' && is_file($publicFile)) {
        $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
        $mimes = [
            'css'   => 'text/css',
            'js'    => 'application/javascript',
            'json'  => 'application/json',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'webp'  => 'image/webp',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf'
        ];
        if (isset($mimes[$ext])) {
            header('Content-Type: ' . $mimes[$ext]);
        }
        readfile($publicFile);
        exit;
    }
}

// Delegate to the main application controller in public/
require_once __DIR__ . '/public/index.php';
