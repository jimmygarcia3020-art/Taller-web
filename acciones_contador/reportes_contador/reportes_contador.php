<?php
header("Content-Type: application/json");

$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");
if ($conexion->connect_error) {
    echo json_encode([]);
    exit;
}

$tipo = $_GET['tipo'] ?? 'ventas';
$tabla = ($tipo === "compras") ? "compras" : "ventas";

$inicio = $_GET['inicio'] ?? "";
$fin = $_GET['fin'] ?? "";

$sql = "SELECT * FROM $tabla WHERE 1=1";

if (!empty($inicio)) $sql .= " AND fecha >= '$inicio'";
if (!empty($fin)) $sql .= " AND fecha <= '$fin'";

$sql .= " ORDER BY fecha ASC";

$result = $conexion->query($sql);
$registros = [];

while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);
?>
