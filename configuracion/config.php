<?php
/**
 * Configuración centralizada del proyecto
 * * Contiene todas las constantes y configuraciones del proyecto
 */

// ===== CARGAR VARIABLES DE ENTORNO =====
$envFile = __DIR__ . '/../.env';
$dbConfig = [
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PASS' => '', // ¡CORRECCIÓN!: Contraseña hardcodeada eliminada por seguridad.
    'DB_NAME' => 'proyecto_taller'
];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (strpos($trimmed, '#') === 0 || $trimmed === '') continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (array_key_exists($key, $dbConfig)) {
                $dbConfig[$key] = $value;
            }
        }
    }
}

// ===== CONFIGURACIÓN DE BASE DE DATOS =====
define('DB_HOST', $dbConfig['DB_HOST']);
define('DB_USER', $dbConfig['DB_USER']);
define('DB_PASS', $dbConfig['DB_PASS']);
define('DB_NAME', $dbConfig['DB_NAME']);

// ===== CONFIGURACIÓN DE RUTAS =====
define('BASE_PATH', dirname(__DIR__) . '/');
define('VISTAS_PATH', BASE_PATH . 'vistas/');
define('CONTROLADORES_PATH', BASE_PATH . 'controladores/');
define('API_PATH', BASE_PATH . 'api/');
define('MODELOS_PATH', BASE_PATH . 'modelos/');
define('PUBLICO_PATH', BASE_PATH . 'publico/');
define('UTILIDADES_PATH', BASE_PATH . 'utilidades/');

// ===== CONFIGURACIÓN DE ERRORES =====
error_reporting(E_ALL);
ini_set('display_errors', 0); // En producción mantener en 0
ini_set('log_errors', 1);

// ===== ARCHIVOS DE UTILIDADES =====
require_once UTILIDADES_PATH . 'sesiones.php';
require_once UTILIDADES_PATH . 'validador.php';
?>