<?php
/**
 * Eliminar registro de reporte
 * Refactorizado: Uso de Singleton y Protección de Sesión
 */

require_once '../../../configuracion/config.php';
require_once '../../../modelos/base_datos.php';

// ¡SEGURIDAD!: Evitar que un usuario no logueado borre datos
requerirAutenticacion();

// Validación estricta de parámetros
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$tipo = isset($_GET["tipo"]) ? trim($_GET["tipo"]) : "";

// Evitar inyección o datos basura
if ($id <= 0 || !in_array($tipo, ['compras', 'ventas'])) {
    header("Location: reportes_contador.html?error=datos_invalidos");
    exit;
}

$db = BaseDatos::obtenerInstancia();
$conexion = $db->getConexion();

$tabla = ($tipo === "compras") ? "compras" : "ventas";

// Consulta preparada segura
$stmt = $conexion->prepare("DELETE FROM $tabla WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Redireccionar de vuelta
header("Location: reportes_contador.html");
exit;
?>