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

// Campos recibidos
$fecha       = isset($data["fecha"]) ? trim($data["fecha"]) : null;
$doc         = isset($data["doc"]) ? trim($data["doc"]) : null;
$entidad     = isset($data["entidad"]) ? trim($data["entidad"]) : null;
$descripcion = isset($data["descripcion"]) ? trim($data["descripcion"]) : "";
$monto       = isset($data["monto"]) ? $data["monto"] : null;
$id_cliente_prov = isset($data["id_cliente"]) ? $data["id_cliente"] : null;
$razon       = isset($data["razon"]) ? trim($data["razon"]) : null; // nombre visible

// Validación mínima
if (!$fecha || !$doc || !$entidad || $monto === null) {
    echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios (fecha, doc, entidad, monto)"]);
    exit;
}

// Asegurar formato numérico del monto
$monto = floatval($monto);

$id_cliente = null;

// 1. Resolver el id_cliente real
if ($id_cliente_prov && is_numeric($id_cliente_prov)) {
    $id_cliente = intval($id_cliente_prov);
} else {
    // Si no enviaron un ID numérico, buscar por nombre o RUC
    $nombre_buscar = $razon ? $razon : $id_cliente_prov;
    
    if (!$nombre_buscar || trim($nombre_buscar) === "") {
        echo json_encode(["status" => "error", "message" => "No se proporcionó ID ni nombre de cliente para buscar"]);
        exit;
    }
    
    // CORRECCIÓN: Se cambió 'id' por 'id_cliente'. Se quitó 'nombre_negocio' porque no existe en la tabla clientes. Se agregó búsqueda por RUC.
    $sel = $conexion->prepare("SELECT id_cliente FROM clientes WHERE nombre = ? OR ruc_dni = ? LIMIT 1");
    if (!$sel) {
        echo json_encode(["status" => "error", "message" => "Error prepare SELECT cliente: " . $conexion->error]);
        exit;
    }
    $sel->bind_param("ss", $nombre_buscar, $nombre_buscar);
    $sel->execute();
    $res = $sel->get_result();
    
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id_cliente = intval($row['id_cliente']); // CORRECCIÓN: 'id_cliente'
        $sel->close();
    } else {
        $sel->close();
        echo json_encode(["status" => "error", "message" => "Cliente no encontrado en la base de datos."]);
        exit;
    }
}

// 2. Insertar en compras
// CORRECCIÓN: Se cambió 'cliente_id' por 'id_cliente' para alinear con contable.sql
$ins = $conexion->prepare("INSERT INTO compras (id_cliente, fecha, doc, entidad, descripcion, monto) VALUES (?, ?, ?, ?, ?, ?)");
if (!$ins) {
    echo json_encode(["status" => "error", "message" => "Error prepare INSERT en compras: " . $conexion->error]);
    exit;
}

// id_cliente(i), fecha(s), doc(s), entidad(s), descripcion(s), monto(d)
$ins->bind_param("issssd", $id_cliente, $fecha, $doc, $entidad, $descripcion, $monto);

if ($ins->execute()) {
    $nuevo_id = $conexion->insert_id;
    echo json_encode(["status" => "success", "message" => "Egreso registrado correctamente", "insert_id" => $nuevo_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al ejecutar INSERT: " . $ins->error]);
}

$ins->close();
?>