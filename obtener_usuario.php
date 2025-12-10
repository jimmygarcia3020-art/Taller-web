<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

// Si no hay sesión, no se puede mostrar
if (!isset($_SESSION['correo'])) {
    echo json_encode(['ok' => false, 'error' => 'No hay sesión activa']);
    exit;
}

$correoSesion = $_SESSION['correo'];

// Conexión BD
$mysqli = new mysqli("localhost", "root", "73442998", "proyecto_taller");
if ($mysqli->connect_errno) {
    echo json_encode(['ok' => false, 'error' => 'Error BD']);
    exit;
}

$stmt = $mysqli->prepare("SELECT nombre_contacto, nombre_negocio, tipo_usuario, correo 
                          FROM datos_registro 
                          WHERE correo = ? LIMIT 1");
$stmt->bind_param("s", $correoSesion);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    echo json_encode(['ok' => true, 'data' => $row]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();
