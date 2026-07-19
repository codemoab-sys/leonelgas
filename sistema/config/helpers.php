<?php

function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError(string $message, int $status = 400): void {
    jsonResponse(['success' => false, 'message' => $message], $status);
}

function baseUrl(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return "$protocol://$host$dir";
}

function assetUrl(string $path): string {
    return baseUrl() . '/assets/' . ltrim($path, '/');
}

function uploadPath(string $filename): string {
    $dir = __DIR__ . '/../uploads/fachadas';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return "$dir/$filename";
}

function uploadUrl(string $filename): string {
    return baseUrl() . '/uploads/fachadas/' . $filename;
}
