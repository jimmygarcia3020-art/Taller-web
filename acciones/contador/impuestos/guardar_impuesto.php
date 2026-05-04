<?php
/**
 * API: Guardar Impuesto
 * Refactorizado: Singleton BD, Respuesta Segura y Control de Acceso
 */

header("Content-Type: application/json; charset=utf-8");

require_once '../../../configuracion/config.php';
require_once '../../../modelos/base_datos.php';

// ¡SEGURIDAD!: Verificar si hay sesión activa para APIs
if (!estaAutenticado()) {
    http_response_code(401); // No autorizado
    echo json_encode(['ok' => false, 'error' => 'Acceso denegado. Inicie sesión.']);
    exit;
}

$db = BaseDatos::obtenerInstancia();
$conexion = $db->getConexion();

// Obtener datos (soporta form-urlencoded POST o JSON)
$input = null;
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);
} else {
    // form POST
    $input = $_POST;
}

// Validación básica
$periodo = trim($input['periodo'] ?? '');
$tipo    = trim($input['tipo'] ?? '');
$monto   = $input['monto'] ?? '';

if ($periodo === '' || $tipo === '' || $monto === '' || !is_numeric($monto) || floatval($monto) <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos. El monto debe ser numérico y mayor a 0.']);
    exit;
}

// Forzar estado "Pagado"
$estado = 'Pagado';
// Convertir monto a float
$monto_float = floatval($monto);

try {
    // Insertar con prepared statement
    $stmt = $conexion->prepare("INSERT INTO impuestos (periodo, tipo, monto, estado) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssds', $periodo, $tipo, $monto_float, $estado);

    if ($stmt->execute()) {
        $nuevo_id = $stmt->insert_id;
        echo json_encode([
            'ok' => true,
            'id' => $nuevo_id,
            'periodo' => $periodo,
            'tipo' => $tipo,
            'monto' => number_format($monto_float, 2, '.', ''), // devolver como string formateado
            'estado' => $estado
        ]);
    } else {
        throw new Exception("Error en la inserción.");
    }
    
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error de servidor interno. Intente más tarde.']);
    // En producción usaríamos: error_log($e->getMessage());
}
?>