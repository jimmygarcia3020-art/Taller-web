<?php
require_once '../../../configuracion/config.php';
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET["id"];
$tipo = $_GET["tipo"];
$tabla = ($tipo == "compras") ? "compras" : "ventas";

$sql = $conexion->prepare("DELETE FROM $tabla WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();

header("Location: reportes_contador.php");
exit;
?>
