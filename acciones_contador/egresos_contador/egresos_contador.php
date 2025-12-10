<?php 
header("Content-Type: application/json");

$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Error de conexión"]);
    exit;
}

// Recibe los datos enviados vía fetch() en JSON
$data = json_decode(file_get_contents("php://input"), true);

$fecha       = $data["fecha"];
$doc         = $data["doc"];         // tipo de comprobante
$entidad     = $data["entidad"];     // RUC o DNI
$descripcion = $data["descripcion"];
$monto       = $data["monto"];       // total final

// Insertar en la tabla compras
$stmt = $conexion->prepare(
    "INSERT INTO compras (fecha, doc, entidad, descripcion, monto) VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param("ssssd", $fecha, $doc, $entidad, $descripcion, $monto);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
