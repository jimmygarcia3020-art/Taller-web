/**
 * Validación de Registro de Usuario
 * Reemplaza: validar.js
 *
 * Valida los campos del formulario de registro
 */

function validar() {
  const nombre = document.getElementById("nombre_contacto").value;
  const negocio = document.getElementById("nombre_negocio").value;
  const numero = document.getElementById("numero_contacto").value;
  const correo = document.getElementById("correo").value;
  const clave = document.getElementById("clave").value;

  // Expresión regular mejorada para email
  const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Validar campos vacíos
  if (
    nombre === "" ||
    negocio === "" ||
    numero === "" ||
    correo === "" ||
    clave === ""
  ) {
    alert("Todos los Campos son Obligatorios");
    return false;
  }

  // Validar nombre
  if (nombre.length > 30) {
    alert("El Nombre es muy Largo (máximo 30 caracteres)");
    return false;
  }

  // Validar negocio
  if (negocio.length > 80) {
    alert("El Nombre del Negocio es muy Largo (máximo 80 caracteres)");
    return false;
  }

  // Validar teléfono
  if (numero.length < 7 || numero.length > 15) {
    alert("El número de Teléfono debe tener entre 7 y 15 caracteres");
    return false;
  }

  if (isNaN(numero)) {
    alert("El número de Teléfono debe contener solo números");
    return false;
  }

  // Validar correo
  if (correo.length > 100) {
    alert("El correo es muy Largo (máximo 100 caracteres)");
    return false;
  }

  if (!expresion.test(correo)) {
    alert("El correo no es válido");
    return false;
  }

  // Validar clave
  if (clave.length < 6 || clave.length > 20) {
    alert("La clave debe tener entre 6 y 20 caracteres");
    return false;
  }

  // Si pasa todas las validaciones
  return true;
}
