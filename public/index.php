<?php

use App\Kernel;

if (PHP_SAPI === 'cli-server') {
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $decodedPath = is_string($requestPath) ? rawurldecode($requestPath) : null;
    $requestedFile = is_string($decodedPath) ? realpath(__DIR__.$decodedPath) : false;
    $publicDirectory = realpath(__DIR__);

    if (
        $requestedFile !== false
        && $publicDirectory !== false
        && str_starts_with($requestedFile, $publicDirectory.DIRECTORY_SEPARATOR)
        && is_file($requestedFile)
    ) {
        $mimeTypes = [
            'css' => 'text/css; charset=utf-8',
            'eot' => 'application/vnd.ms-fontobject',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'js' => 'application/javascript; charset=utf-8',
            'json' => 'application/json; charset=utf-8',
            'map' => 'application/json; charset=utf-8',
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'ttf' => 'font/ttf',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
        $extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));

        if (isset($mimeTypes[$extension])) {
            header('Content-Type: '.$mimeTypes[$extension]);
            header('X-Content-Type-Options: nosniff');
            header('Content-Length: '.filesize($requestedFile));
            readfile($requestedFile);
            exit;
        }

        return false;
    }
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};