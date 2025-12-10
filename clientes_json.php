<?php
header('Content-Type: application/json');

// CONEXIÓN
$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error DB"]));
}

// BUSQUEDA (SI EXISTE q)
$q = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : "";

// consulta base
$sql = "SELECT id, nombre FROM clientes";

// si hay texto en el buscador
if (!empty($q)) {
    $sql .= " WHERE nombre LIKE '%$q%' OR ruc_dni LIKE '%$q%'";
}

$sql .= " ORDER BY nombre ASC";

$res = $conexion->query($sql);

// preparar salida
$clientes = [];
while ($row = $res->fetch_assoc()) {
    $clientes[] = [
        "id" => $row["id"],
        "nombre" => $row["nombre"]
    ];
}

echo json_encode($clientes);
?>
