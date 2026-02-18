<?php
/**
 * Inicialización común para el panel de administración.
 * Configura sesión segura y carga configuración.
 */

$config = include dirname(__DIR__, 2) . '/config/config.php';

// Configuración segura de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_name($config['session_name'] ?? 'ALADA_SESSION');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

/**
 * Genera un token CSRF y lo guarda en sesión.
 */
function getCsrfToken(array $config): string {
    $name = $config['csrf_token_name'] ?? 'csrf_token';
    if (empty($_SESSION[$name])) {
        $_SESSION[$name] = bin2hex(random_bytes(32));
    }
    return $_SESSION[$name];
}

/**
 * Verifica el token CSRF del formulario.
 */
function validateCsrfToken(array $config): bool {
    $name = $config['csrf_token_name'] ?? 'csrf_token';
    $token = $_POST[$name] ?? '';
    return !empty($_SESSION[$name]) && hash_equals($_SESSION[$name], $token);
}
