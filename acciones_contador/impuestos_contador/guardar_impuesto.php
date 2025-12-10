<?php
// guardar_impuesto.php
header("Content-Type: application/json; charset=utf-8");

// --- Configuración de DB (ajusta) ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '73442998');
define('DB_NAME', 'proyecto_taller');

// Habilitar reporte de errores mysqli en desarrollo (comenta en producción)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Conectar
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

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
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos.']);
    exit;
}

// Forzar estado "Pagado"
$estado = 'Pagado';

// Convertir monto a float (no string)
$monto_float = floatval($monto);

try {
    // Insertar con prepared statement
    $stmt = $mysqli->prepare("INSERT INTO impuestos (periodo, tipo, monto, estado) VALUES (?, ?, ?, ?)");
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
        // Esto normalmente no ocurre si mysqli_report está activo; se captura en catch
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error execute: ' . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Excepción: ' . $e->getMessage()]);
} finally {
    $mysqli->close();
}
