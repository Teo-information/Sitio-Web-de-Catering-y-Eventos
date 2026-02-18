<?php
/**
 * Archivo de ejemplo de configuración.
 * Copia este archivo a config.local.php y ajusta los valores.
 * config.local.php no debe subirse al repositorio (añadir a .gitignore).
 */

return [
    'admin_username' => 'admin',
    'admin_password_hash' => '$2y$10$mbE697e6ud594IGIa/0Dy.HgszObCQIrypk743qGcnpfGVwiRurRS', // Hash de 'alada2025'
    'session_name' => 'ALADA_SESSION',
    'csrf_token_name' => 'csrf_token',
    'debug' => false, // true solo en desarrollo - evita exponer detalles en errores
];
