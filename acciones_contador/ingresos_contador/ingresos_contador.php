<?php
header("Content-Type: application/json");

$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");

if ($conexion->connect_error) {
    echo json_encode(["status"=>"error","message"=>"Error de conexión"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$fecha = $data["fecha"];
$tipo = $data["tipo"];
$serie = $data["serie"];
$numero = $data["numero"];
$cliente = $data["cliente"];
$descripcion = $data["descripcion"];
$monto = $data["monto"];

$doc = $tipo . " " . $serie . "-" . $numero;

$stmt = $conexion->prepare(
    "INSERT INTO ventas (fecha, doc, entidad, descripcion, monto) VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param("ssssd", $fecha, $doc, $cliente, $descripcion, $monto);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
}

$stmt->close();
$conexion->close();
?>
