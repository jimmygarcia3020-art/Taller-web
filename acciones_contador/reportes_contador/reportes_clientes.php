<?php
header("Content-Type: application/json; charset=utf-8");
$mysqli = new mysqli("localhost","root","73442998","proyecto_taller");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"DB connection failed: ".$mysqli->connect_error]);
    exit;
}

$cliente_id = isset($_GET['cliente_id']) ? trim($_GET['cliente_id']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
$desde = isset($_GET['desde']) ? trim($_GET['desde']) : '';
$hasta = isset($_GET['hasta']) ? trim($_GET['hasta']) : '';

// DEBUG: devolver lo que llegó (temporal)
$debug = [
    'cliente_id_received' => $cliente_id,
    'tipo' => $tipo,
    'desde' => $desde,
    'hasta' => $hasta
];

// validar cliente_id: si viene vacío -> devolvemos todos (o vacio, según prefieras)
$filterByCliente = false;
if ($cliente_id !== '' && is_numeric($cliente_id)) {
    $cliente_id = intval($cliente_id);
    $filterByCliente = true;
}

// construir condiciones comunes
$dateSql = "";
$params = [];
$types = "";

if ($desde !== '') {
    $dateSql .= " AND fecha >= ? ";
    $types .= "s"; $params[] = $desde;
}
if ($hasta !== '') {
    $dateSql .= " AND fecha <= ? ";
    $types .= "s"; $params[] = $hasta;
}

$rows = [];

try {
    // si tipo vacío o 'ventas' -> consultar ventas
    if ($tipo === '' || $tipo === 'ventas' || $tipo === 'ambos') {
        $sql = "SELECT id, fecha, doc, entidad, descripcion, monto, 'ventas' AS tipo FROM ventas WHERE 1=1 ";
        if ($filterByCliente) {
            $sql .= " AND cliente_id = ? ";
        }
        $sql .= $dateSql . " ORDER BY fecha DESC, id DESC LIMIT 200";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) throw new Exception("prepare ventas failed: " . $mysqli->error);

        // bind dinámico: cliente primero si existe, luego fechas
        $bindVals = [];
        $bindTypes = "";
        if ($filterByCliente) { $bindTypes .= "i"; $bindVals[] = $cliente_id; }
        $bindTypes .= $types;
        foreach ($params as $p) $bindVals[] = $p;

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

    // si tipo vacío o 'compras' -> consultar compras
    if ($tipo === '' || $tipo === 'compras' || $tipo === 'ambos') {
        $sql = "SELECT id, fecha, doc, entidad, descripcion, monto, 'compras' AS tipo FROM compras WHERE 1=1 ";
        if ($filterByCliente) {
            // en compras tu columna se llama id_cliente (según menciones previas)
            $sql .= " AND cliente_id = ? ";
        }
        $sql .= $dateSql . " ORDER BY fecha DESC, id DESC LIMIT 200";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) throw new Exception("prepare compras failed: " . $mysqli->error);

        $bindVals = [];
        $bindTypes = "";
        if ($filterByCliente) { $bindTypes .= "i"; $bindVals[] = $cliente_id; }
        $bindTypes .= $types;
        foreach ($params as $p) $bindVals[] = $p;

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

    // ordenar resultados por fecha desc (si mezcló ambas tablas)
    usort($rows, function($a,$b){
        if ($a['fecha'] === $b['fecha']) return $b['id'] <=> $a['id'];
        return strcmp($b['fecha'], $a['fecha']);
    });

    $total = 0.0;
    foreach ($rows as $r) $total += floatval($r['monto']);

    echo json_encode(["status"=>"success","debug"=>$debug,"data"=>$rows,"total"=>$total]);

} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>$ex->getMessage(),"debug"=>$debug]);
} finally {
    $mysqli->close();
}
