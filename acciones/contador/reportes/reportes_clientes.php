<?php
// reportes_clientes.php
header("Content-Type: application/json; charset=utf-8");

// Evitar que PHP muestre warnings en la salida JSON
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once '../../../configuracion/config.php';
require_once '../../../modelos/base_datos.php';

// Uso del Singleton para la conexión
$db = BaseDatos::obtenerInstancia();
$mysqli = $db->getConexion();

$nombre_cliente = isset($_GET['cliente']) ? trim($_GET['cliente']) : '';
$cliente_id_prov = isset($_GET['cliente_id']) ? trim($_GET['cliente_id']) : '';
$tipo           = isset($_GET['tipo']) ? trim($_GET['tipo']) : ''; // ventas|compras|ambos|''
$desde          = isset($_GET['desde']) ? trim($_GET['desde']) : '';
$hasta          = isset($_GET['hasta']) ? trim($_GET['hasta']) : '';

$response = ["status" => "success", "data" => [], "total" => 0.0, "debug" => []];
$id_cliente_real = null;

try {
    // 1) Si se mandó nombre (o RUC) y no ID, buscar el id_cliente correcto
    if ($cliente_id_prov === '' && $nombre_cliente !== '') {
        // CORRECCIÓN: Se cambió 'id' por 'id_cliente' y se busca también por ruc_dni
        $stmt = $mysqli->prepare("SELECT id_cliente FROM clientes WHERE nombre = ? OR ruc_dni = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $nombre_cliente, $nombre_cliente);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $id_cliente_real = intval($row['id_cliente']);
            }
            $stmt->close();
        }
    } else if (is_numeric($cliente_id_prov)) {
        $id_cliente_real = intval($cliente_id_prov);
    }

    // Si no pudimos identificar al cliente, devolvemos un reporte vacío con una advertencia
    if (!$id_cliente_real) {
        $response['message'] = "No se pudo identificar al cliente en la base de datos.";
        echo json_encode($response);
        exit;
    }

    // 2) Preparar condiciones dinámicas de fechas
    $dateConds = "";
    $bindTypes = "i"; // El primer parámetro siempre será el id_cliente (integer)
    $bindVals = [$id_cliente_real];

    if ($desde !== '') {
        $dateConds .= " AND fecha >= ?";
        $bindTypes .= "s";
        $bindVals[] = $desde;
    }
    if ($hasta !== '') {
        $dateConds .= " AND fecha <= ?";
        $bindTypes .= "s";
        $bindVals[] = $hasta;
    }

    $rows = [];

    // 3) Consultar VENTAS
    if ($tipo === '' || $tipo === 'ventas' || $tipo === 'ambos') {
        // CORRECCIÓN: Se cambió 'cliente_id' por 'id_cliente'
        $sql = "SELECT id, fecha, doc, entidad, descripcion, monto, 'ventas' AS tipo
                FROM ventas
                WHERE id_cliente = ? $dateConds
                ORDER BY fecha DESC, id DESC
                LIMIT 1000";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            // Forma moderna y limpia de pasar parámetros dinámicos en PHP
            $stmt->bind_param($bindTypes, ...$bindVals);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $stmt->close();
        }
    }

    // 4) Consultar COMPRAS (Egresos)
    if ($tipo === '' || $tipo === 'compras' || $tipo === 'ambos') {
        // CORRECCIÓN: Se cambió 'cliente_id' por 'id_cliente'
        $sql = "SELECT id, fecha, doc, entidad, descripcion, monto, 'compras' AS tipo
                FROM compras
                WHERE id_cliente = ? $dateConds
                ORDER BY fecha DESC, id DESC
                LIMIT 1000";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($bindTypes, ...$bindVals);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $stmt->close();
        }
    }

    // 5) Ordenar por fecha descendente en PHP si se mezclaron ventas y compras
    usort($rows, function($a, $b) {
        if ($a['fecha'] === $b['fecha']) return $b['id'] <=> $a['id'];
        return strcmp($b['fecha'], $a['fecha']);
    });

    // 6) Calcular total (Ingresos suman, Compras restan)
    $total = 0.0;
    foreach ($rows as $r) {
        if ($r['tipo'] === 'ventas') {
            $total += floatval($r['monto']);
        } else {
            $total -= floatval($r['monto']); // Los egresos restan al flujo total
        }
    }

    $response['data'] = $rows;
    $response['total'] = number_format($total, 2, '.', '');

} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = "Error interno del servidor.";
    // En un entorno de producción, registraríamos $e->getMessage() en un log interno
}

echo json_encode($response);
?>