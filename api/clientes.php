<?php
/**
 * API de Clientes
 * Reemplaza: clientes_json.php + obtener_usuario.php
 * 
 * Endpoints:
 * - GET: buscar clientes
 * - POST: obtener datos del usuario autenticado
 */

require_once '../../configuracion/config.php';
require_once MODELOS_PATH . 'base_datos.php';

header('Content-Type: application/json; charset=utf-8');

requerirAutenticacion();

$bd = BaseDatos::obtenerInstancia();
$conexion = $bd->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Buscar clientes por nombre o RUC
    $q = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : "";

    $sql = "SELECT id, nombre FROM clientes";

    if (!empty($q)) {
        $sql .= " WHERE nombre LIKE '%$q%' OR ruc_dni LIKE '%$q%'";
    }

    $sql .= " ORDER BY nombre ASC";

    $res = $conexion->query($sql);

    $clientes = [];
    while ($row = $res->fetch_assoc()) {
        $clientes[] = [
            "id" => $row["id"],
            "nombre" => $row["nombre"]
        ];
    }

    echo json_encode($clientes);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del usuario autenticado
    $correoSesion = obtenerCorreoUsuario();

    $stmt = $conexion->prepare("SELECT nombre_contacto, nombre_negocio, tipo_usuario, correo 
                              FROM datos_registro 
                              WHERE correo = ? LIMIT 1");
    $stmt->bind_param("s", $correoSesion);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        echo json_encode(['ok' => true, 'data' => $row]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

$conexion->close();
?>
