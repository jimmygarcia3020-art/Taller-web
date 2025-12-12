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
$descripcion = $data["ruc"];
$monto = $data["monto"];
$nombre_cliente = $data["id_cliente"];

$stmt = $conexion->prepare("SELECT id FROM clientes WHERE nombre = ?");
$stmt->bind_param("s", $nombre_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_cliente = $row["id"]; 
} else {
    echo json_encode(["status"=>"error", "message"=>"Cliente no encontrado"]);
    exit;
}

$stmt->close();

$doc = $tipo . " " . $serie . "-" . $numero;

$stmt = $conexion->prepare(
    "INSERT INTO ventas (fecha, doc, entidad, descripcion, monto, cliente_id) VALUES (?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param("ssssdi", $fecha, $doc, $cliente, $descripcion, $monto, $id_cliente);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
}

$stmt->close();
$conexion->close();
?>
