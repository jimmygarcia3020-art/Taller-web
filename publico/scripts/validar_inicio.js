/**
 * validar_inicio.js
 * -----------------
 * Lógica completa de la página de inicio de sesión de ContaPlus.
 * Gestiona: validación de campos, bloqueo por intentos fallidos,
 * toggle de contraseña y feedback visual inline.
 *
 * NO hay ningún handler inline (onsubmit, onclick, etc.) en el PHP.
 * Todo se enlaza aquí mediante addEventListener en DOMContentLoaded.
 */

/* ==========================================================
   CONSTANTES DE CONFIGURACIÓN
   Valores ajustables sin tocar la lógica de validación.
========================================================== */
const CONFIG = {
  MAX_INTENTOS:      3,          // Intentos antes de bloquear
  BLOQUEO_SEGUNDOS: 30,          // Duración del bloqueo en segundos
  EMAIL_MAX_LENGTH: 100,         // Longitud máxima permitida para el correo
  CLAVE_MIN_LENGTH:  6,          // Longitud mínima de la contraseña
  CLAVE_MAX_LENGTH: 20,          // Longitud máxima de la contraseña
};

/* ==========================================================
   ESTADO DEL MÓDULO
   Variables de estado aisladas en este archivo para evitar
   contaminar el scope global.
========================================================== */
let intentos      = 0;           // Contador de intentos fallidos acumulados
let bloqueadoHasta = 0;          // Timestamp (ms) hasta el que está bloqueado
let countdownInterval = null;    // Referencia al intervalo del contador regresivo

/* ==========================================================
   SELECTORES CACHEADOS
   Se obtienen una sola vez al cargar el módulo.
========================================================== */
const ELEMENTOS = {
  form:          null,   // Se asigna en init()
  correo:        null,
  clave:         null,
  btnSubmit:     null,
  togglePassword: null,
  iconEyeOff:    null,
  iconEyeOn:     null,
  errorCorreo:   null,
  errorClave:    null,
  lockBanner:    null,
  lockMessage:   null,
  lockBar:       null,
  lockCountdown: null,
};

/* ==========================================================
   MENSAJES DE ERROR
   Textos centralizados para mantener consistencia en el UI.
========================================================== */
const ERRORES = {
  camposVacios:    'Este campo es obligatorio.',
  correoLargo:     `El correo no debe superar ${CONFIG.EMAIL_MAX_LENGTH} caracteres.`,
  correoInvalido:  'Ingresa un correo electrónico válido.',
  claveCorta:      `La contraseña debe tener al menos ${CONFIG.CLAVE_MIN_LENGTH} caracteres.`,
  claveLarga:      `La contraseña no debe superar ${CONFIG.CLAVE_MAX_LENGTH} caracteres.`,
};

/* ==========================================================
   mostrarError()
   Muestra un mensaje de error debajo de un campo específico
   y aplica el borde rojo al input para feedback visual.
   @param {HTMLElement} campo    - El input con error.
   @param {HTMLElement} spanErr  - El <span> donde se muestra el texto.
   @param {string}      mensaje  - Texto del error.
========================================================== */
function mostrarError(campo, spanErr, mensaje) {
  spanErr.textContent = mensaje;
  spanErr.classList.add('visible');
  campo.classList.add('border-red-400');
  campo.classList.remove('border-border-sutil');
}

/* ==========================================================
   limpiarError()
   Elimina el mensaje de error y restaura el borde normal.
   @param {HTMLElement} campo   - El input a limpiar.
   @param {HTMLElement} spanErr - El <span> del error.
========================================================== */
function limpiarError(campo, spanErr) {
  spanErr.textContent = '';
  spanErr.classList.remove('visible');
  campo.classList.remove('border-red-400');
  campo.classList.add('border-border-sutil');
}

/* ==========================================================
   sacudirFormulario()
   Aplica una animación shake al card del formulario para
   dar feedback visual cuando hay un intento fallido.
   Remueve la clase después de que termina para poder
   reutilizarla en el siguiente intento.
========================================================== */
function sacudirFormulario() {
  const card = ELEMENTOS.form.closest('.bg-surface-card');
  if (!card) return;
  card.classList.add('animate-shake');
  card.addEventListener('animationend', () => {
    card.classList.remove('animate-shake');
  }, { once: true });
}

/* ==========================================================
   mostrarBloqueo()
   Muestra el banner de bloqueo con un contador regresivo
   y una barra de progreso que se reduce cada segundo.
   Deshabilita el botón de submit durante el bloqueo.
   @param {number} segundos - Duración del bloqueo.
========================================================== */
function mostrarBloqueo(segundos) {
  const { lockBanner, lockCountdown, lockBar, btnSubmit } = ELEMENTOS;

  lockBanner.classList.remove('hidden');
  btnSubmit.disabled = true;

  // Barra de progreso: empieza llena (100%) y se vacía en 'segundos' seg
  lockBar.style.width = '100%';
  lockBar.style.transition = `width ${segundos}s linear`;

  // Forzar reflow para que la transición arrange desde 100%
  void lockBar.offsetWidth;
  lockBar.style.width = '0%';

  // Actualiza el contador cada segundo
  lockCountdown.textContent = segundos;
  clearInterval(countdownInterval);

  countdownInterval = setInterval(() => {
    segundos -= 1;
    lockCountdown.textContent = segundos;

    if (segundos <= 0) {
      clearInterval(countdownInterval);
      ocultarBloqueo();
    }
  }, 1000);
}

/* ==========================================================
   ocultarBloqueo()
   Oculta el banner y rehabilita el botón de submit cuando
   termina el período de bloqueo.
========================================================== */
function ocultarBloqueo() {
  ELEMENTOS.lockBanner.classList.add('hidden');
  ELEMENTOS.btnSubmit.disabled = false;
  bloqueadoHasta = 0;
  intentos = 0;      // Reinicia el contador tras el desbloqueo
}

/* ==========================================================
   validar()
   Lógica de validación principal. Mantiene la misma lógica
   del archivo original pero con feedback inline en lugar
   de alert(). Retorna true si pasa todas las validaciones.
   @returns {boolean}
========================================================== */
function validar() {
  const correo = ELEMENTOS.correo.value.trim();
  const clave  = ELEMENTOS.clave.value;
  const { errorCorreo, errorClave } = ELEMENTOS;

  // Limpiar errores previos antes de re-validar
  limpiarError(ELEMENTOS.correo, errorCorreo);
  limpiarError(ELEMENTOS.clave,  errorClave);

  // --- Verificar si está bloqueado ---
  if (bloqueadoHasta && new Date().getTime() < bloqueadoHasta) {
    const tiempoRestante = Math.ceil((bloqueadoHasta - new Date().getTime()) / 1000);
    mostrarBloqueo(tiempoRestante);
    sacudirFormulario();
    return false;
  }

  let esValido = true;

  // --- Validación del correo ---
  if (!correo) {
    mostrarError(ELEMENTOS.correo, errorCorreo, ERRORES.camposVacios);
    esValido = false;
  } else if (correo.length > CONFIG.EMAIL_MAX_LENGTH) {
    mostrarError(ELEMENTOS.correo, errorCorreo, ERRORES.correoLargo);
    esValido = false;
  } else {
    const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!expresion.test(correo)) {
      mostrarError(ELEMENTOS.correo, errorCorreo, ERRORES.correoInvalido);
      esValido = false;
    }
  }

  // --- Validación de la contraseña ---
  if (!clave) {
    mostrarError(ELEMENTOS.clave, errorClave, ERRORES.camposVacios);
    esValido = false;
  } else if (clave.length < CONFIG.CLAVE_MIN_LENGTH) {
    mostrarError(ELEMENTOS.clave, errorClave, ERRORES.claveCorta);
    esValido = false;
  } else if (clave.length > CONFIG.CLAVE_MAX_LENGTH) {
    mostrarError(ELEMENTOS.clave, errorClave, ERRORES.claveLarga);
    esValido = false;
  }

  // --- Si falla, sumar intento y verificar si debe bloquear ---
  if (!esValido) {
    intentos += 1;
    sacudirFormulario();

    if (intentos >= CONFIG.MAX_INTENTOS) {
      bloqueadoHasta = new Date().getTime() + CONFIG.BLOQUEO_SEGUNDOS * 1000;
      mostrarBloqueo(CONFIG.BLOQUEO_SEGUNDOS);
    }
  }

  return esValido;
}

/* ==========================================================
   initTogglePassword()
   Maneja el botón de ojo para mostrar/ocultar la contraseña.
   Alterna el type del input entre 'password' y 'text',
   y cambia los íconos correspondientes.
========================================================== */
function initTogglePassword() {
  const { togglePassword, clave, iconEyeOff, iconEyeOn } = ELEMENTOS;
  if (!togglePassword) return;

  togglePassword.addEventListener('click', () => {
    const esPassword = clave.type === 'password';

    // Alterna el tipo del input
    clave.type = esPassword ? 'text' : 'password';

    // Alterna los íconos
    iconEyeOff.classList.toggle('hidden', esPassword);
    iconEyeOn.classList.toggle('hidden', !esPassword);

    // Actualiza el aria-label para accesibilidad
    togglePassword.setAttribute(
      'aria-label',
      esPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
    );
  });
}

/* ==========================================================
   initLimpiezaEnTipoReal()
   Limpia el error de un campo en cuanto el usuario empieza
   a escribir de nuevo, para no dejar el estado de error
   mientras el usuario ya está corrigiendo.
========================================================== */
function initLimpiezaEnTipoReal() {
  ELEMENTOS.correo.addEventListener('input', () => {
    limpiarError(ELEMENTOS.correo, ELEMENTOS.errorCorreo);
  });
  ELEMENTOS.clave.addEventListener('input', () => {
    limpiarError(ELEMENTOS.clave, ELEMENTOS.errorClave);
  });
}

/* ==========================================================
   initFormSubmit()
   Enlaza el evento submit del formulario con la función
   validar(). Si la validación falla, previene el envío.
   No hay onsubmit en el HTML.
========================================================== */
function initFormSubmit() {
  ELEMENTOS.form.addEventListener('submit', (e) => {
    if (!validar()) {
      e.preventDefault(); // Bloquea el envío al servidor si hay errores
    }
  });
}

/* ==========================================================
   init()
   Cachea todos los elementos del DOM, luego inicializa
   cada módulo de interacción.
   Se ejecuta solo cuando el DOM está completamente listo.
========================================================== */
function init() {
  // Cachear elementos del DOM una sola vez
  ELEMENTOS.form           = document.getElementById('loginForm');
  ELEMENTOS.correo         = document.getElementById('correo');
  ELEMENTOS.clave          = document.getElementById('clave');
  ELEMENTOS.btnSubmit      = document.getElementById('btnSubmit');
  ELEMENTOS.togglePassword = document.getElementById('togglePassword');
  ELEMENTOS.iconEyeOff     = document.getElementById('iconEyeOff');
  ELEMENTOS.iconEyeOn      = document.getElementById('iconEyeOn');
  ELEMENTOS.errorCorreo    = document.getElementById('errorCorreo');
  ELEMENTOS.errorClave     = document.getElementById('errorClave');
  ELEMENTOS.lockBanner     = document.getElementById('lockBanner');
  ELEMENTOS.lockMessage    = document.getElementById('lockMessage');
  ELEMENTOS.lockBar        = document.getElementById('lockBar');
  ELEMENTOS.lockCountdown  = document.getElementById('lockCountdown');

  // Salir si el formulario no existe (protección ante carga parcial)
  if (!ELEMENTOS.form) {
    console.warn('[validar_inicio.js] No se encontró el formulario #loginForm.');
    return;
  }

  initFormSubmit();          // Validación al enviar
  initTogglePassword();      // Mostrar/ocultar contraseña
  initLimpiezaEnTipoReal();  // Limpiar errores al escribir
}

// Punto de entrada único: espera a que el DOM esté completamente parseado
document.addEventListener('DOMContentLoaded', init);