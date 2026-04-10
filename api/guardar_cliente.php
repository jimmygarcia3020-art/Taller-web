<?php
/**
 * API: Guardar Cliente
 * Reemplaza: guardar_cliente.php
 * 
 * Guarda datos de cliente en la tabla clientes
 */

require_once '../../configuracion/config.php';
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
    $nombre_negocio = $data['nombre_negocio'] ?? '';
    $tipo_cliente = $data['tipo_cliente'] ?? '';
    $telefono = $data['telefono'] ?? '';
    $email = $data['email'] ?? '';
    $ruc_dni = $data['ruc_dni'] ?? '';

    // Validación básica
    if (empty($nombre) || empty($email)) {
        throw new Exception("Nombre y email son requeridos");
    }

    // Preparar insert
    $stmt = $conexion->prepare(
        "INSERT INTO clientes (nombre, nombre_negocio, tipo_cliente, telefono, email, ruc_dni)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception("Error prepare: " . $conexion->error);
    }

    $stmt->bind_param("ssssss", $nombre, $nombre_negocio, $tipo_cliente, $telefono, $email, $ruc_dni);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Cliente guardado correctamente',
            'id' => $conexion->insert_id
        ]);
    } else {
        throw new Exception("Error execute: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conexion->close();
?>
