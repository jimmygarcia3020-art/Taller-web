<?php
/**
 * Utilidades de sesiones
 * * Centraliza manejo de sesiones y autenticación reforzada
 */

// Forzar parámetros seguros de cookie ANTES de iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Prevenir caché del navegador (Para páginas autenticadas)
 */
function prevenirCache() {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
}

/**
 * Verificar si hay sesión activa
 */
function estaAutenticado() {
    return isset($_SESSION['correo']);
}

/**
 * Redirigir si no hay sesión
 */
function requerirAutenticacion() {
    prevenirCache();
    if (!estaAutenticado()) {
        header("Location: ../../vistas/autenticacion/inicio_sesion.php");
        exit;
    }
}

/**
 * Cerrar sesión de manera segura
 */
function cerrarSesion() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
    prevenirCache();
    header("Location: ../../vistas/autenticacion/inicio_sesion.php");
    exit;
}

// ==========================================
// FUNCIONES DE OBTENCIÓN DE DATOS
// ==========================================

/**
 * Obtener correo del usuario autenticado (Soluciona el Fatal Error)
 */
function obtenerCorreoUsuario() {
    return $_SESSION['correo'] ?? null;
}

/**
 * Obtener ID del usuario autenticado
 */
function obtenerIdUsuario() {
    return $_SESSION['id_usuario'] ?? null;
}

// ==========================================
// NUEVAS FUNCIONES DE SEGURIDAD PARA EL ROADMAP
// ==========================================

function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>