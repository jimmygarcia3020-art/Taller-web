<?php
// reportes_clientes.php
header("Content-Type: application/json; charset=utf-8");
// evitar que PHP muestre warnings en la salida JSON
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$mysqli = new mysqli("localhost","root","73442998","proyecto_taller");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"DB connection failed"]);
    exit;
}

$nombre_cliente = isset($_GET['cliente']) ? trim($_GET['cliente']) : '';
$cliente_id     = isset($_GET['cliente_id']) ? trim($_GET['cliente_id']) : '';
$tipo           = isset($_GET['tipo']) ? trim($_GET['tipo']) : ''; // ventas|compras|ambos|''
$desde          = isset($_GET['desde']) ? trim($_GET['desde']) : '';
$hasta          = isset($_GET['hasta']) ? trim($_GET['hasta']) : '';

$response = ["status"=>"success","data"=>[], "total"=>0.0, "debug"=>[]];

try {
    // 1) si se mandó nombre y no id, buscar id
    if ($cliente_id === '' && $nombre_cliente !== '') {
        $stmt = $mysqli->prepare("SELECT id FROM clientes WHERE nombre = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $nombre_cliente);
            $stmt->execute();
            $stmt->bind_result($found_id);
            if ($stmt->fetch()) {
                $cliente_id = (int)$found_id;
            }
            $stmt->close();
        }
    } else if ($cliente_id !== '') {
        // aceptar id numérico únicamente
        if (!is_numeric($cliente_id)) $cliente_id = '';
        else $cliente_id = (int)$cliente_id;
    }

    if ($cliente_id === '' || $cliente_id === 0) {
        // devolver vacio (es preferible devolver éxito con data vacía)
        echo json_encode($response);
        $mysqli->close();
        exit;
    }

    // preparar condiciones de fecha y binding
    $dateConds = "";
    $bindTypes = "i"; // primero siempre cliente_id (int)
    $bindVals  = [$cliente_id];

    if ($desde !== '') {
        $dateConds .= " AND fecha >= ? ";
        $bindTypes .= "s";
        $bindVals[] = $desde;
    }
    if ($hasta !== '') {
        $dateConds .= " AND fecha <= ? ";
        $bindTypes .= "s";
        $bindVals[] = $hasta;
    }

    // consultar ventas y compras por separado para mayor control
    $rows = [];

    if ($tipo === '' || $tipo === 'ventas' || $tipo === 'ambos') {
        $sql = "SELECT id, fecha, doc, entidad, descripcion, monto, 'ventas' AS tipo
                FROM ventas
                WHERE cliente_id = ? $dateConds
                ORDER BY fecha DESC, id DESC
                LIMIT 1000";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            // bind dinámico
            if ($bindTypes !== "") {
                $refs = [];
                $refs[] = & $bindTypes;
                foreach ($bindVals as $k => $v) $refs[] = & $bindVals[$k];
                call_user_func_array([$stmt, 'bind_param'], $refs);
            }
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $stmt->close();
        }
    }

    if ($tipo === '' || $tipo === 'compras' || $tipo === 'ambos') {
        $sql = "SELECT id, fecha, doc, entidad, descripcion, monto, 'compras' AS tipo
                FROM compras
                WHERE cliente_id = ? $dateConds
                ORDER BY fecha DESC, id DESC
                LIMIT 1000";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            if ($bindTypes !== "") {
                $refs = [];
                $refs[] = & $bindTypes;
                foreach ($bindVals as $k => $v) $refs[] = & $bindVals[$k];
                call_user_func_array([$stmt, 'bind_param'], $refs);
            }
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $rows[] = $r;
            $stmt->close();
        }
    }

    // ordenar por fecha desc en PHP por si mezcló ambas tablas
    usort($rows, function($a,$b){
        if ($a['fecha'] === $b['fecha']) return $b['id'] <=> $a['id'];
        return strcmp($b['fecha'], $a['fecha']);
    });

    $total = 0.0;
    foreach ($rows as $r) $total += floatval($r['monto']);

    $response['data'] = $rows;
    $response['total'] = $total;
    $response['debug'] = ["cliente_id_used" => $cliente_id, "count" => count($rows)];

    echo json_encode($response);

} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>$ex->getMessage()]);
} finally {
    $mysqli->close();
}
