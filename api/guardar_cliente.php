<?php
/**
 * API: Guardar Cliente
 * Guarda datos de cliente en la tabla clientes
 */

require_once '../configuracion/config.php';
require_once MODELOS_PATH . 'base_datos.php';

header("Content-Type: application/json; charset=utf-8");

requerirAutenticacion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$bd = BaseDatos::obtenerInstancia();
$conexion = $bd->getConexion();

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("No se recibieron datos");
    }

    // Extraer datos
    $nombre = $data['nombre'] ?? '';
    $tipo_cliente = $data['tipo_cliente'] ?? 'NATURAL';
    $telefono = $data['telefono'] ?? '';
    $email = $data['email'] ?? '';
    $ruc_dni = $data['ruc_dni'] ?? '';
    // CORRECCIÓN: Se eliminó $nombre_negocio porque no existe en la tabla `clientes` de contable.sql
    
    // Validación básica
    if (empty($nombre) || empty($email)) {
        throw new Exception("Nombre y email son requeridos");
    }

    // Preparar insert
    // CORRECCIÓN: Eliminado nombre_negocio de la consulta
    $stmt = $conexion->prepare(
        "INSERT INTO clientes (nombre, tipo_cliente, telefono, email, ruc_dni)
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception("Error prepare: " . $conexion->error);
    }

    $stmt->bind_param("sssss", $nombre, $tipo_cliente, $telefono, $email, $ruc_dni);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Cliente guardado correctamente',
            'id' => $conexion->insert_id // Devuelve el id_cliente generado
        ]);
    } else {
        throw new Exception("Error al guardar: " . $stmt->error);
    }
    
    $stmt->close();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>