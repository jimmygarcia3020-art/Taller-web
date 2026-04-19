<?php
/**
 * Controlador de Autenticación
 * Reemplaza: if1.php + resultado.php + if.php
 *
 * Maneja inicio de sesión, registro y cierre de sesión
 *
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
        header("Location: ../../vistas/autenticacion/inicio_sesion.php");
        exit();
}

/**
 * Iniciar sesión (Login)
 */
function iniciarSesion()
{
    prevenirCache();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../../vistas/autenticacion/inicio_sesion.php");
        exit();
    }

    // Validar entrada
    $resultado = Validador::validarInicioSesion($_POST);
    if (!$resultado["valido"]) {
        $_SESSION["errores"] = $resultado["errores"];
        header("Location: ../../vistas/autenticacion/inicio_sesion.php");
        exit();
    }

    $correo = trim($_POST["correo"]);
    $clave = trim($_POST["clave"]);

    // Conectar a BD
    $bd = BaseDatos::obtenerInstancia();
    $conexion = $bd->getConexion();

    $sql = $conexion->prepare(
        "SELECT id, clave, tipo_usuario FROM usuarios WHERE correo = ?",
    );
    $sql->bind_param("s", $correo);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($clave, $usuario["clave"])) {
            $_SESSION["correo"] = $correo;
            $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["intentos"] = 0;
            $_SESSION["bloqueado_hasta"] = 0;

            // Redirigir según tipo de usuario
            if ($usuario["tipo_usuario"] === "Cliente") {
                header("Location: ../../vistas/cliente/panel_principal.php");
            } elseif ($usuario["tipo_usuario"] === "Contador") {
                header("Location: ../../vistas/contador/panel_principal.php");
            } else {
                header(
                    "Location: ../../vistas/autenticacion/inicio_sesion.php",
                );
            }
            exit();
        } else {
            // Contraseña incorrecta
            $_SESSION["intentos"] = ($_SESSION["intentos"] ?? 0) + 1;

            if ($_SESSION["intentos"] >= 3) {
                $_SESSION["bloqueado_hasta"] = time() + 3 * 60; // 3 minutos
                $_SESSION["intentos"] = 0;
                echo "<script>
                    alert('🚫 Excediste el número de intentos. Vuelve a intentarlo en 3 minutos.');
                    window.location.href = '../../vistas/autenticacion/inicio_sesion.php';
                </script>";
            } else {
                $restantes = 3 - $_SESSION["intentos"];
                echo "<script>
                    alert('❌ Clave incorrecta. Intentos restantes: $restantes');
                    window.location.href = '../../vistas/autenticacion/inicio_sesion.php';
                </script>";
            }
        }
    } else {
        echo "<script>
            alert('⚠️ Usuario no encontrado');
            window.location.href = '../../vistas/autenticacion/inicio_sesion.php';
        </script>";
    }

    $sql->close();
}

/**
 * Registrar nuevo usuario
 */
function registrarUsuario()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../../vistas/autenticacion/registro.php");
        exit();
    }

    // Validar entrada
    $resultado = Validador::validarRegistroUsuario($_POST);
    if (!$resultado["valido"]) {
        $_SESSION["errores"] = $resultado["errores"];
        header("Location: ../../vistas/autenticacion/registro.php");
        exit();
    }

    $nombre_contacto = trim($_POST["nombre_contacto"]);
    $nombre_negocio = trim($_POST["nombre_negocio"]);
    $numero_contacto = trim($_POST["numero_contacto"]);
    $tipo_usuario = trim($_POST["tipo_usuario"]); // rol: Cliente / Contador
    $correo = trim($_POST["correo"]);
    $clave = $_POST["clave"];
    $tipo_cliente = trim($_POST["tipo_cliente"] ?? ""); // clasificación dentro de clientes (opcional)
    $regimen = trim($_POST["regimen"] ?? "");

    // Validación server-side: si es Cliente entonces tipo_cliente debe estar presente
    if (strcasecmp($tipo_usuario, "Cliente") === 0 && $tipo_cliente === "") {
        $_SESSION["errores"] = [
            "tipo_cliente" =>
                "El tipo de cliente es obligatorio cuando el tipo de usuario es Cliente.",
        ];
        header("Location: ../../vistas/autenticacion/registro.php");
        exit();
    }

    // Conectar a BD
    $bd = BaseDatos::obtenerInstancia();
    $conexion = $bd->getConexion();

    // Verificar si el correo ya existe
    $check = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = ?");
    $check->bind_param("s", $correo);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
            alert('⚠️ Este correo ya está registrado. Inicia sesión.');
            window.location.href = '../../vistas/autenticacion/inicio_sesion.php';
        </script>";
        $check->close();
        exit();
    }

    $check->close();

    // Hash de contraseña
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // INSERT 1: Tabla usuarios
        $sql1 = $conexion->prepare(
            "INSERT INTO usuarios (correo, clave, tipo_usuario) VALUES (?, ?, ?)",
        );
        if (!$sql1) {
            throw new Exception("Error SQL1: " . $conexion->error);
        }

        $sql1->bind_param("sss", $correo, $clave_hash, $tipo_usuario);
        if (!$sql1->execute()) {
            throw new Exception("Error SQL1 execute: " . $sql1->error);
        }

        // INSERT 2: Tabla datos_registro
        $sql2 = $conexion->prepare(
            "INSERT INTO datos_registro (nombre_contacto, nombre_negocio, numero_contacto, tipo_usuario, correo, regimen) VALUES (?, ?, ?, ?, ?, ?)",
        );
        if (!$sql2) {
            throw new Exception("Error SQL2: " . $conexion->error);
        }

        $sql2->bind_param(
            "ssssss",
            $nombre_contacto,
            $nombre_negocio,
            $numero_contacto,
            $tipo_usuario,
            $correo,
            $regimen,
        );
        if (!$sql2->execute()) {
            throw new Exception("Error SQL2 execute: " . $sql2->error);
        }

        // INSERT 3: Si es cliente, insertar en tabla clientes
        if (strcasecmp($tipo_usuario, "Cliente") === 0) {
            // La tabla `clientes` en el diagrama no contiene `nombre_negocio`.
            // Insertamos solo las columnas que existen: nombre, tipo_cliente, telefono, email.
            $sql3 = $conexion->prepare("
                INSERT INTO clientes (nombre, tipo_cliente, telefono, email)
                VALUES (?, ?, ?, ?)
            ");
            if (!$sql3) {
                throw new Exception("Error SQL3: " . $conexion->error);
            }

            $sql3->bind_param(
                "ssss",
                $nombre_contacto,
                $tipo_cliente,
                $numero_contacto,
                $correo,
            );
            if (!$sql3->execute()) {
                throw new Exception("Error SQL3 execute: " . $sql3->error);
            }
            $sql3->close();
        }

        $conexion->commit();

        echo "<script>
            alert('✅ Registro exitoso. Ahora puedes iniciar sesión.');
            window.location.href = '../../vistas/autenticacion/inicio_sesion.php';
        </script>";

        $sql1->close();
        $sql2->close();
    } catch (Exception $e) {
        $conexion->rollback();
        echo "<script>
            alert('❌ Error durante el registro: " .
            addslashes($e->getMessage()) .
            "');
            window.history.back();
        </script>";
    }
}

/**
 * Cerrar sesión
 */
function cerrarSesionUsuario()
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            "",
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"],
        );
    }

    session_destroy();
    prevenirCache();

    echo "<script>
        alert('Has cerrado sesión correctamente.');
        window.location.href = '../../vistas/autenticacion/inicio_sesion.php';
    </script>";
    exit();
}
?>
