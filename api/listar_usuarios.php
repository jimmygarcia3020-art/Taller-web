<?php
/**
 * API: Listar Usuarios
 */

require_once '../configuracion/config.php';
require_once MODELOS_PATH . 'base_datos.php';

header("Content-Type: application/json; charset=utf-8");

$bd = BaseDatos::obtenerInstancia();
$conexion = $bd->getConexion();

try {
    // Traer el registro más reciente (La tabla datos_registro usa 'id', lo cual es correcto)
    $sql = "SELECT id, nombre_contacto, nombre_negocio, numero_contacto, tipo_usuario, correo, regimen
            FROM datos_registro
            ORDER BY id DESC
            LIMIT 1";
    
    $res = $conexion->query($sql);

    if ($res && $row = $res->fetch_assoc()) {
        echo json_encode(['ok' => true, 'data' => $row]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'No hay registros']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error de servidor interno.']);
    // Loguear $e->getMessage() en un archivo en producción
}
?>