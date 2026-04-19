
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Taller Contable</title>
    <link rel="stylesheet" href="../../publico/estilos/principal.css">
    <script src="../../publico/scripts/validar_registro.js"></script>
</head>
<body>
    <section class="form-register">
        <h4>Formulario de Registro</h4>
        <form action="../../controladores/autenticacion.php?accion=registro" method="POST" onsubmit="return validar();">
            <input class="controls" type="text" name="nombre_contacto" id="nombre_contacto"
                   placeholder="Ingrese su Nombre Completo" required>
            <input class="controls" type="text" name="nombre_negocio" id="nombre_negocio"
                   placeholder="Ingrese su nombre de negocio" required>
            <input class="controls" type="tel" name="numero_contacto" id="numero_contacto"
                   placeholder="Ingrese su Número de Contacto" required>

            <select class="controls" name="tipo_usuario" id="tipo_usuario" required>
                <option value="">Seleccione Tipo de Usuario</option>
                <option value="Cliente">Cliente</option>
                <option value="Contador">Contador</option>
            </select>

            <input class="controls" type="email" name="correo" id="correo"
                   placeholder="Ingrese su Correo" required>
            <input class="controls" type="password" name="clave" id="clave"
                   placeholder="Ingrese su Clave" required>

            <select class="controls" name="tipo_cliente" id="tipo_cliente">
                <option value="">Seleccione Tipo de Cliente</option>
                <option value="NATURAL">Natural</option>
                <option value="JURIDICO">Jurídico</option>
            </select>

            <select class="controls" name="regimen" id="regimen" required>
                <option value="">Selecciona tu régimen</option>
                <option value="NRUS">Nuevo Régimen Único Simplificado</option>
                <option value="RER">Régimen Especial de Impuesto a la Renta</option>
                <option value="RMT">Régimen MYPE Tributario</option>
                <option value="RG">Régimen General</option>
            </select>

            <input class="botons" type="submit" value="Registrar">
            <p><a href="inicio_sesion.php">¿Ya tienes cuenta? Inicia sesión aquí</a></p>
        </form>
    </section>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const tipoUsuario = document.getElementById('tipo_usuario');
  const tipoCliente = document.getElementById('tipo_cliente');

  function toggleTipoCliente() {
    if (!tipoUsuario || !tipoCliente) return;
    if (tipoUsuario.value === 'Cliente') {
      tipoCliente.style.display = '';
      tipoCliente.required = true;
    } else {
      tipoCliente.style.display = 'none';
      tipoCliente.required = false;
      tipoCliente.value = '';
    }
  }

  tipoUsuario.addEventListener('change', toggleTipoCliente);
  toggleTipoCliente(); // estado inicial
});
</script>
</body>
</html>
