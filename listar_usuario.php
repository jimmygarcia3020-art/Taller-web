<?php
// listar_usuario.php
header("Content-Type: application/json; charset=utf-8");

// --- Ajusta tus credenciales ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '73442998');
define('DB_NAME', 'proyecto_taller');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $mysqli->set_charset('utf8mb4');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error conexión DB: ' . $e->getMessage()]);
    exit;
}

try {
    // Traer el registro más reciente. Si quieres uno específico, cambia la consulta.
    $sql = "SELECT id, nombre_contacto, nombre_negocio, numero_contacto, tipo_usuario, correo, factura, regimen
            FROM datos_registro
            ORDER BY id DESC
            LIMIT 1";
    $res = $mysqli->query($sql);

    if ($row = $res->fetch_assoc()) {
        // Normalizar tipos
        $row['id'] = (int)$row['id'];
        echo json_encode(['ok' => true, 'data' => $row]);
    } else {
        // No hay registros
        echo json_encode(['ok' => true, 'data' => null]);
    }

    $res->free();
    $mysqli->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
