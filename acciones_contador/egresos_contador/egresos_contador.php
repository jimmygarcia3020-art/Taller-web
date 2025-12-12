<?php
header("Content-Type: application/json; charset=utf-8");

$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");
if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Error de conexión: " . $conexion->connect_error]);
    exit;
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);
if (!is_array($data)) {
    echo json_encode(["status" => "error", "message" => "JSON inválido"]);
    exit;
}

// Campos recibidos
$fecha       = isset($data["fecha"]) ? $data["fecha"] : null;
$doc         = isset($data["doc"]) ? $data["doc"] : null;
$entidad     = isset($data["entidad"]) ? $data["entidad"] : null;
$descripcion = isset($data["descripcion"]) ? $data["descripcion"] : "";
$monto       = isset($data["monto"]) ? $data["monto"] : null;
$id_cliente_prov = isset($data["id_cliente"]) ? $data["id_cliente"] : null;
$razon       = isset($data["razon"]) ? $data["razon"] : null; // nombre visible

// Validación mínima
if (!$fecha || !$doc || !$entidad || $monto === null) {
    echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios (fecha, doc, entidad, monto)"]);
    exit;
}

$monto = floatval($monto);
$id_cliente = null;

// 1) Si id_cliente numeric fue provisto, validamos y usamos
if ($id_cliente_prov !== null && $id_cliente_prov !== "" && is_numeric($id_cliente_prov)) {
    $check = $conexion->prepare("SELECT id FROM clientes WHERE id = ? LIMIT 1");
    if (!$check) {
        echo json_encode(["status" => "error", "message" => "Error prepare check cliente: " . $conexion->error]);
        exit;
    }
    $idc = intval($id_cliente_prov);
    $check->bind_param("i", $idc);
    $check->execute();
    $res = $check->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id_cliente = intval($row['id']);
    } else {
        $check->close();
        echo json_encode(["status" => "error", "message" => "El id_cliente proporcionado no existe"]);
        exit;
    }
    $check->close();
} else {
    // 2) Fallback: buscar por razon (nombre o nombre_negocio)
    $nombre_buscar = $razon ?: $id_cliente_prov;
    if (!$nombre_buscar || trim($nombre_buscar) === "") {
        echo json_encode(["status" => "error", "message" => "No se proporcionó id_cliente ni nombre para buscar"]);
        exit;
    }
    $sel = $conexion->prepare("SELECT id FROM clientes WHERE nombre = ? OR nombre_negocio = ? LIMIT 1");
    if (!$sel) {
        echo json_encode(["status" => "error", "message" => "Error prepare SELECT cliente: " . $conexion->error]);
        exit;
    }
    $sel->bind_param("ss", $nombre_buscar, $nombre_buscar);
    $sel->execute();
    $res = $sel->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id_cliente = intval($row['id']);
        $sel->close();
    } else {
        $sel->close();
        echo json_encode(["status" => "error", "message" => "Cliente no encontrado por nombre/negocio"]);
        exit;
    }
}

// Insertar en compras
$ins = $conexion->prepare("INSERT INTO compras (fecha, doc, entidad, descripcion, monto, cliente_id) VALUES (?, ?, ?, ?, ?, ?)");
if (!$ins) {
    echo json_encode(["status" => "error", "message" => "Error prepare INSERT: " . $conexion->error]);
    exit;
}
$ins->bind_param("ssssdi", $fecha, $doc, $entidad, $descripcion, $monto, $id_cliente);

if ($ins->execute()) {
    echo json_encode(["status" => "success", "insert_id" => $ins->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => $ins->error]);
}

$ins->close();
$conexion->close();
?>
