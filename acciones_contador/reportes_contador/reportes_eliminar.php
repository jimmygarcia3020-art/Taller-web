<?php
$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");

$id = $_GET["id"];
$tipo = $_GET["tipo"];
$tabla = ($tipo == "compras") ? "compras" : "ventas";

$sql = $conexion->prepare("DELETE FROM $tabla WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();

header("Location: reportes_contador.html");
exit;
?>
