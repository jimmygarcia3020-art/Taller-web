<?php
header("Content-Type: application/json; charset=utf-8");

$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");
if ($conexion->connect_error) {
    echo json_encode(["error" => "DB connection", "message" => $conexion->connect_error]);
    exit;
}


$cliente_id = isset($_GET['cliente_id']) ? trim($_GET['cliente_id']) : '';

if ($cliente_id === '' || !is_numeric($cliente_id)) {
    // devolver 0s si no hay cliente válido
    echo json_encode(["ingresos_total" => 0.0, "egresos_total" => 0.0]);
    exit;
}

$cliente_id = intval($cliente_id);

// SUMA en ventas (tabla ventas, columna monto, campo cliente_id)
$sql1 = "SELECT IFNULL(SUM(monto),0) AS total FROM ventas WHERE cliente_id = ?";
$stmt1 = $conexion->prepare($sql1);
if (!$stmt1) {
    echo json_encode(["error" => "prepare1", "message" => $conexion->error]);
    exit;
}
$stmt1->bind_param("i", $cliente_id);
$stmt1->execute();
$res1 = $stmt1->get_result();
$row1 = $res1->fetch_assoc();
$ingresos_total = floatval($row1['total']);
$stmt1->close();

// SUMA en compras (tabla compras, columna monto, campo id_cliente)
$sql2 = "SELECT IFNULL(SUM(monto),0) AS total FROM compras WHERE cliente_id = ?";
$stmt2 = $conexion->prepare($sql2);
if (!$stmt2) {
    echo json_encode(["error" => "prepare2", "message" => $conexion->error]);
    exit;
}
$stmt2->bind_param("i", $cliente_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$row2 = $res2->fetch_assoc();
$egresos_total = floatval($row2['total']);
$stmt2->close();

$conexion->close();

echo json_encode([
    "ingresos_total" => $ingresos_total,
    "egresos_total" => $egresos_total
]);
