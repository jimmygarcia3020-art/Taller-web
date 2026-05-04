<?php
/**
 * Utilidades de sesiones
 * * Centraliza manejo de sesiones y autenticación reforzada
 */

// Forzar parámetros seguros de cookie ANTES de iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0, // Expira al cerrar el navegador
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']), // Solo por HTTPS si está disponible
        'httponly' => true, // Previene robo de sesión mediante JavaScript (XSS)
        'samesite' => 'Lax' // Previene ataques CSRF inter-sitio
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
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    prevenirCache();
    header("Location: ../../vistas/autenticacion/inicio_sesion.php");
    exit;
}

// ==========================================
// NUEVAS FUNCIONES DE SEGURIDAD PARA EL ROADMAP
// ==========================================

/**
 * Genera un token CSRF para formularios
 */
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida un token CSRF recibido
 */
function validarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>