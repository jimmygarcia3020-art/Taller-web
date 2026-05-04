<?php
/**
 * Controlador de Autenticación
 * Maneja inicio de sesión, registro y cierre de sesión
 */

require_once __DIR__ . "/../configuracion/config.php";
require_once MODELOS_PATH . "base_datos.php";

// Determinar acción
$accion = $_GET["accion"] ?? ($_POST["accion"] ?? "");

switch ($accion) {
    case "login":
        iniciarSesion();
        break;
    case "registro":
        registrarUsuario();
        break;
    case "logout":
        cerrarSesion();
        break;
    default:
        header("Location: ../vistas/autenticacion/inicio_sesion.php");
        exit();
}

/**
 * Iniciar sesión (Login)
 */
function iniciarSesion() {
    prevenirCache();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../vistas/autenticacion/inicio_sesion.php");
        exit();
    }

    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if (empty($correo) || empty($clave)) {
        echo "<script>alert('Por favor, complete todos los campos.'); window.history.back();</script>";
        exit;
    }

    try {
        $db = BaseDatos::obtenerInstancia();
        $conexion = $db->getConexion();

        $stmt = $conexion->prepare("SELECT id, correo, clave, tipo_usuario FROM usuarios WHERE correo = ?");
        if (!$stmt) throw new Exception("Error al preparar la consulta de usuarios.");
        
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($usuario = $resultado->fetch_assoc()) {
            // Verificar contraseña (Asegúrate de que al registrar usas password_hash)
            if (password_verify($clave, $usuario['clave'])) {
                
                // ¡SEGURIDAD CRÍTICA!: Regenerar ID para evitar Session Fixation
                session_regenerate_id(true);

                $_SESSION['id_usuario'] = $usuario['id'];
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

                // Redirección según rol
                if (strtoupper($usuario['tipo_usuario']) === 'CONTADOR') {
                    header("Location: ../vistas/contador/panel_principal.php");
                } else {
                    header("Location: ../vistas/cliente/panel_principal.php");
                }
                exit();
            } else {
                echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('El correo no está registrado.'); window.history.back();</script>";
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log("Error en Login: " . $e->getMessage());
        echo "<script>alert('Ocurrió un error interno. Intente más tarde.'); window.history.back();</script>";
    }
}

/**
 * Registro de Usuario
 */
function registrarUsuario() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../vistas/autenticacion/registro.php");
        exit();
    }

    // Datos principales
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'CLIENTE';
    
    // Datos secundarios
    $nombre_contacto = trim($_POST['nombre_contacto'] ?? '');
    $nombre_negocio = trim($_POST['nombre_negocio'] ?? '');
    $numero_contacto = trim($_POST['numero_contacto'] ?? '');

    if (empty($correo) || empty($clave)) {
        echo "<script>alert('El correo y la contraseña son obligatorios.'); window.history.back();</script>";
        exit;
    }

    try {
        $db = BaseDatos::obtenerInstancia();
        $conexion = $db->getConexion();

        // Iniciar transacción (Si falla uno, fallan todos)
        $conexion->begin_transaction();

        // 1. Insertar en tabla `usuarios`
        $hash_clave = password_hash($clave, PASSWORD_DEFAULT);
        $stmt1 = $conexion->prepare("INSERT INTO usuarios (correo, clave, tipo_usuario) VALUES (?, ?, ?)");
        if (!$stmt1) throw new Exception("Error prepare usuarios: " . $conexion->error);
        
        $stmt1->bind_param("sss", $correo, $hash_clave, $tipo_usuario);
        $stmt1->execute();
        $stmt1->close();

        // 2. Insertar en tabla `datos_registro`
        $stmt2 = $conexion->prepare("INSERT INTO datos_registro (nombre_contacto, nombre_negocio, numero_contacto, tipo_usuario, correo) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt2) throw new Exception("Error prepare datos_registro: " . $conexion->error);
        
        $stmt2->bind_param("sssss", $nombre_contacto, $nombre_negocio, $numero_contacto, $tipo_usuario, $correo);
        $stmt2->execute();
        $stmt2->close();

        // Confirmar cambios
        $conexion->commit();

        echo "<script>
            alert('✅ Registro exitoso. Ahora puedes iniciar sesión.');
            window.location.href = '../vistas/autenticacion/inicio_sesion.php';
        </script>";

    } catch (Exception $e) {
        $conexion->rollback(); // Deshacer en caso de error
        error_log("Error en Registro: " . $e->getMessage());
        
        // Mensaje genérico para no exponer errores SQL al cliente
        echo "<script>
            alert('❌ Ocurrió un error al registrar. Asegúrate de que el correo no esté duplicado.');
            window.history.back();
        </script>";
    }
}
?>