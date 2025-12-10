<?php
$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$nombre_contacto = $_POST['nombre_contacto'];
$nombre_negocio = $_POST['nombre_negocio'];
$numero_contacto = $_POST['numero_contacto'];
$tipo_usuario = $_POST['tipo_usuario'];
$correo = $_POST['correo'];
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
$tipo_cliente = $_POST['tipo_cliente'];
$regimen = $_POST['Regimen'];

$check = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = ?");
$check->bind_param("s", $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>
        alert('⚠️ Este correo ya está registrado. Inicia sesión.');
        window.location.href = 'index_registros.html';
    </script>";
    $check->close();
    $conexion->close();
    exit;
}

$conexion->begin_transaction();

try {
    $sql1 = $conexion->prepare("INSERT INTO usuarios (correo, clave, tipo_usuario) VALUES (?, ?, ?)");
    if (!$sql1) {
        throw new Exception("Error preparando SQL1: " . $conexion->error);
    }
    $sql1->bind_param("sss", $correo, $clave, $tipo_usuario);
    if (!$sql1->execute()) {
        throw new Exception("Error ejecutando SQL1: " . $sql1->error);
    }

    $sql2 = $conexion->prepare("INSERT INTO datos_registro (nombre_contacto, nombre_negocio, numero_contacto, tipo_usuario, correo, tipo_cliente, regimen) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$sql2) {
        throw new Exception("Error preparando SQL2: " . $conexion->error);
    }
    $sql2->bind_param("ssissss", $nombre_contacto, $nombre_negocio, $numero_contacto, $tipo_usuario, $correo, $tipo_cliente, $regimen);
    if (!$sql2->execute()) {
        throw new Exception("Error ejecutando SQL2: " . $sql2->error);
    }

    $sql3 = $conexion->prepare("INSERT INTO clientes (nombre, tipo_cliente, telefono, email)  VALUES (?, ?, ?, ?)");
    if (!$sql3) {
        throw new Exception("Error preparando SQL3: " . $conexion->error);
    }
    $sql3->bind_param("sssss", $nombre_contacto, $tipo_cliente, $numero_contacto, $correo);
    if (!$sql3->execute()) {
        throw new Exception("Error ejecutando SQL3: " . $sql3->error);
    }

    $conexion->commit();

    echo "<script>
        alert('✅ Registro exitoso. Ahora puedes iniciar sesión.');
        window.location.href = 'index1.html';
    </script>";

} catch (Exception $e) {
    $conexion->rollback();
    echo "<script>
        alert('❌ Error durante el registro: " . addslashes($e->getMessage()) . "');
        window.history.back();
    </script>";
}

$conexion->close();
?>
