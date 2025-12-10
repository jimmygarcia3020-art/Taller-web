<?php
session_start();

$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller"); 

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$correo = $_POST["correo"];
$clave = $_POST["clave"];

$sql = $conexion->prepare("SELECT clave, tipo_usuario FROM usuarios WHERE correo = ?");
$sql->bind_param("s", $correo);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($clave, $usuario['clave'])) {
        $_SESSION["correo"] = $correo;
        $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

        switch ($usuario["tipo_usuario"]) {
            case "Cliente":
                header("Location: http://localhost/Taller_web/index_cliente.html");
                break;
            case "Contador":
                header("Location: http://localhost/Taller_web/index_contador.html");
                break;
            default:
                header("Location: http://localhost/Taller_web/index1.html");
        }
        exit;
    } else {
        $_SESSION['intentos']++;

        if ($_SESSION['intentos'] >= 3) {
            $_SESSION['bloqueado_hasta'] = time() + (3 * 60); // Bloqueo de 3 minutos
            $_SESSION['intentos'] = 0; // Reinicia el contador
            echo "<script>
                alert('🚫 Excediste el número de intentos. Vuelve a intentarlo en 3 minutos.');
                window.location.href = 'index1.html';
            </script>";
        } else {
            $restantes = 3 - $_SESSION['intentos'];
            echo "<script>
                alert('❌ Clave incorrecta. Intentos restantes: $restantes');
                window.location.href = 'index1.html';
            </script>";
        }
    }
} else {
    echo "<script>
        alert('⚠️ Usuario no encontrado');
        window.location.href = 'index1.html';
    </script>";
}

$conexion->close();
?>
