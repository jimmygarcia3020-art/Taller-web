<?php
/**
 * Utilidades de sesiones
 * Reemplaza a: cerrar_sesion.php 
 * 
 * Centraliza manejo de sesiones y autenticación
 */

// Inicia sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Prevenir caché del navegador
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
 * Cerrar sesión
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

/**
 * Obtener correo del usuario autenticado
 */
function obtenerCorreoUsuario() {
    return $_SESSION['correo'] ?? null;
}

/**
 * Obtener tipo de usuario autenticado
 */
function obtenerTipoUsuario() {
    return $_SESSION['tipo_usuario'] ?? null;
}

/**
 * Obtener datos de sesión
 */
function obtenerDatosUsuario() {
    return [
        'correo' => obtenerCorreoUsuario(),
        'tipo' => obtenerTipoUsuario()
    ];
}
?>
