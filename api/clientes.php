<?php
/**
 * API de Clientes
 * Endpoints:
 * - GET: buscar clientes
 * - POST: obtener datos del usuario autenticado
 */

require_once '../configuracion/config.php';
require_once MODELOS_PATH . 'base_datos.php';

header('Content-Type: application/json; charset=utf-8');

requerirAutenticacion();

$bd = BaseDatos::obtenerInstancia();
$conexion = $bd->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Buscar clientes por nombre o RUC
    $q = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : "";

    // CORRECCIÓN: Se cambió 'id' por 'id_cliente' para coincidir con la BD
    $sql = "SELECT id_cliente, nombre FROM clientes";

    if (!empty($q)) {
        $sql .= " WHERE nombre LIKE '%$q%' OR ruc_dni LIKE '%$q%'";
    }

    $sql .= " ORDER BY nombre ASC";

    $res = $conexion->query($sql);

    $clientes = [];
    while ($row = $res->fetch_assoc()) {
        $clientes[] = [
            "id" => $row["id_cliente"], // Mantenemos "id" en el JSON para no romper el JS del frontend
            "nombre" => $row["nombre"]
        ];
    }

    echo json_encode($clientes);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del usuario autenticado
    // Nota: Asumiendo que obtenerCorreoUsuario() existe en sesiones.php, si no, usa $_SESSION['correo']
    $correoSesion = $_SESSION['correo'] ?? '';

    $stmt = $conexion->prepare("SELECT nombre_contacto, nombre_negocio, tipo_usuario, correo 
                              FROM datos_registro 
                              WHERE correo = ? LIMIT 1");
    $stmt->bind_param("s", $correoSesion);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        echo json_encode(['ok' => true, 'usuario' => $row]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado en registro']);
    }
    $stmt->close();
}
?>