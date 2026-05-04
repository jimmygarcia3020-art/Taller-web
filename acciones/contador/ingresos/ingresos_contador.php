<?php
header("Content-Type: application/json; charset=utf-8");

require_once '../../../configuracion/config.php';
require_once '../../../modelos/base_datos.php';

// Uso del Singleton para la conexión
$db = BaseDatos::obtenerInstancia();
$conexion = $db->getConexion();

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode(["status" => "error", "message" => "JSON inválido"]);
    exit;
}

// Recolección de datos (asegurando existencia)
$fecha          = isset($data["fecha"]) ? trim($data["fecha"]) : null;
$tipo           = isset($data["tipo"]) ? trim($data["tipo"]) : "";
$serie          = isset($data["serie"]) ? trim($data["serie"]) : "";
$numero         = isset($data["numero"]) ? trim($data["numero"]) : "";
$cliente        = isset($data["cliente"]) ? trim($data["cliente"]) : ""; // Entidad
$descripcion    = isset($data["descripcion"]) ? trim($data["descripcion"]) : (isset($data["ruc"]) ? trim($data["ruc"]) : "");
$monto          = isset($data["monto"]) ? floatval($data["monto"]) : null;
$id_cliente_prov = isset($data["id_cliente"]) ? $data["id_cliente"] : null;
$nombre_cliente = isset($data["nombre_cliente"]) ? trim($data["nombre_cliente"]) : null;

// Validación mínima
if (!$fecha || $monto === null) {
    echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios (fecha, monto)"]);
    exit;
}

$id_cliente = null;

// 1. Resolver el id_cliente real
if ($id_cliente_prov && is_numeric($id_cliente_prov)) {
    $id_cliente = intval($id_cliente_prov);
} else {
    // Si no enviaron un ID numérico, buscar por nombre o RUC
    $nombre_buscar = $nombre_cliente ? $nombre_cliente : $cliente;
    
    if (!$nombre_buscar || trim($nombre_buscar) === "") {
        echo json_encode(["status" => "error", "message" => "No se proporcionó ID ni nombre de cliente para buscar"]);
        exit;
    }
    
    // CORRECCIÓN: Se cambió 'id' por 'id_cliente' y se agregó búsqueda por RUC
    $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE nombre = ? OR ruc_dni = ? LIMIT 1");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Error prepare SELECT cliente: " . $conexion->error]);
        exit;
    }
    $stmt->bind_param("ss", $nombre_buscar, $nombre_buscar);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_cliente = intval($row["id_cliente"]); // CORRECCIÓN: 'id_cliente'
    } else {
        $stmt->close();
        echo json_encode(["status" => "error", "message" => "Cliente no encontrado en la base de datos."]);
        exit;
    }
    $stmt->close();
}

// Construir el documento
$doc = trim($tipo . " " . $serie . "-" . $numero);
if ($doc === "-") $doc = "S/D"; // Si no enviaron documento

// 2. Insertar en ventas
// CORRECCIÓN: Se cambió 'cliente_id' por 'id_cliente' para alinear con contable.sql
$stmt = $conexion->prepare("INSERT INTO ventas (id_cliente, fecha, doc, entidad, descripcion, monto) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error al preparar INSERT: " . $conexion->error]);
    exit;
}

// id_cliente(i), fecha(s), doc(s), entidad(s), descripcion(s), monto(d)
$stmt->bind_param("issssd", $id_cliente, $fecha, $doc, $cliente, $descripcion, $monto);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Venta registrada con éxito"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al guardar el ingreso: " . $stmt->error]);
}

$stmt->close();
?>