<?php
/**
 * Configuración de la aplicación.
 * Carga config.local.php si existe (para overrides locales), sino usa valores por defecto.
 */

$defaultConfig = [
    'admin_username' => 'admin',
    'admin_password_hash' => '$2y$10$mbE697e6ud594IGIa/0Dy.HgszObCQIrypk743qGcnpfGVwiRurRS',
    'session_name' => 'ALADA_SESSION',
    'csrf_token_name' => 'csrf_token',
    'debug' => false,
];

$localConfigFile = __DIR__ . '/config.local.php';
if (file_exists($localConfigFile)) {
    $localConfig = include $localConfigFile;
    if (is_array($localConfig)) {
        $defaultConfig = array_merge($defaultConfig, $localConfig);
    }
}

return $defaultConfig;
