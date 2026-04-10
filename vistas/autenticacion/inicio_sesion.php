
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Taller Contable</title>
    <link rel="stylesheet" href="../../publico/estilos/principal.css">
    <script src="../../publico/scripts/validar_inicio.js"></script>
</head>
<body>
    <section class="form-register">
        <h4>Inicio de Sesión</h4>
        <form action="../../controladores/autenticacion.php?accion=login" method="POST" onsubmit="return validar();">
            <input class="controls" type="email" name="correo" id="correo" placeholder="Ingrese su Correo" required>
            <input class="controls" type="password" name="clave" id="clave" placeholder="Ingrese su Clave" required>
            <input class="botons" type="submit" value="Iniciar Sesión">
            <p><a href="registro.php">¿No tienes cuenta? Regístrate aquí</a></p>
        </form>
    </section>
</body>
</html>
