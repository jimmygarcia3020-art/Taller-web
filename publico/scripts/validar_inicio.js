/**
 * Validación de Inicio de Sesión
 * Reemplaza: validar1.js
 *
 * Valida los campos del formulario de inicio de sesión
 */

let intentos = 0;
let bloqueadoHasta = 0;

function validar() {
  const correo = document.getElementById("correo").value;
  const clave = document.getElementById("clave").value;

  // Verificar si está bloqueado
  if (bloqueadoHasta && new Date().getTime() < bloqueadoHasta) {
    const tiempoRestante = Math.ceil(
      (bloqueadoHasta - new Date().getTime()) / 1000,
    );
    alert(`🚫 Cuenta bloqueada. Intenta en ${tiempoRestante} segundos.`);
    return false;
  }

  // Expresión regular mejorada para email
  const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Validaciones
  if (correo === "" || clave === "") {
    alert("Todos los Campos son Obligatorios");
    return false;
  }

  if (correo.length > 100) {
    alert("El correo es muy Largo");
    return false;
  }

  if (!expresion.test(correo)) {
    alert("El correo no es válido");
    return false;
  }

  if (clave.length < 6 || clave.length > 20) {
    alert("La clave debe tener entre 6 y 20 caracteres");
    return false;
  }

  // Si pasa todas las validaciones, permitir envío
  return true;
}
