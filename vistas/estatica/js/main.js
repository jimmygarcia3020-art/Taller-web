
const SELECTORS = {
  nav: 'nav',
  menuToggleBtn: '#menuToggleBtn',
  mobileMenu: '#mobileMenu',
  mobileLinks: '.mobile__link',
  anchorLinks: 'a[href^="#"]',
  contactForm: '#contactForm',
  btnSoftwareDesktop: '#btnSoftwareDesktop',
  btnSoftwareMobile: '#btnSoftwareMobile',
};

const SOFTWARE_URL = '../autenticacion/inicio_sesion.php';

const MESSAGES = {
  success: '¡Gracias por contactarnos! Te responderemos pronto.',
  error: 'Por favor, completa todos los campos correctamente.',
  emailInvalid: 'El correo electrónico no tiene un formato válido.',
};

function initNavScroll() {
  const nav = document.querySelector(SELECTORS.nav);
  if (!nav) return;

  // Clases que se alternan al hacer scroll
  const scrolledClasses = ['bg-white', 'shadow-md'];
  const defaultClasses  = ['bg-white/92', 'backdrop-blur-xl'];

  function onScroll() {
    if (window.scrollY > 20) {
      // Baja: fondo blanco sólido + sombra, sin blur translúcido
      defaultClasses.forEach(cls => nav.classList.remove(cls));
      scrolledClasses.forEach(cls => nav.classList.add(cls));
    } else {
      // Arriba: vuelve al estilo glassmorphism original
      scrolledClasses.forEach(cls => nav.classList.remove(cls));
      defaultClasses.forEach(cls => nav.classList.add(cls));
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // Ejecutar una vez al cargar por si la página recarga a mitad
}

function initMobileMenu() {
  const toggleBtn  = document.querySelector(SELECTORS.menuToggleBtn);
  const mobileMenu = document.querySelector(SELECTORS.mobileMenu);
  if (!toggleBtn || !mobileMenu) return;

  // Abre/cierra el menú al pulsar el botón hamburguesa
  toggleBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });

  // Cierra el menú al hacer click en cualquier enlace interno
  document.querySelectorAll(SELECTORS.mobileLinks).forEach((link) => {
    link.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
    });
  });
}

function initSoftwareButtons() {
  [SELECTORS.btnSoftwareDesktop, SELECTORS.btnSoftwareMobile].forEach((sel) => {
    const btn = document.querySelector(sel);
    if (btn) btn.addEventListener('click', () => { location.href = SOFTWARE_URL; });
  });
}

function initSmoothScroll() {
  document.querySelectorAll(SELECTORS.anchorLinks).forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}

function validateFormData({ nombre, apellido, email, mensaje }) {
  if (!nombre || !apellido || !email || !mensaje) {
    return { valid: false, message: MESSAGES.error };
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return { valid: false, message: MESSAGES.emailInvalid };
  }

  return { valid: true, message: '' };
}

function handleFormSubmit(e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const data = {
    nombre:   formData.get('nombre')?.trim(),
    apellido: formData.get('apellido')?.trim(),
    email:    formData.get('email')?.trim(),
    mensaje:  formData.get('mensaje')?.trim(),
  };

  const { valid, message } = validateFormData(data);

  if (!valid) {
    showNotification(message, 'error');
    return;
  }

  // Aquí iría el fetch() al servidor. Por ahora se simula.
  console.info('[main.js] Datos del formulario:', data);

  showNotification(MESSAGES.success, 'success');
  e.target.reset();
}

function initContactForm() {
  const form = document.querySelector(SELECTORS.contactForm);
  if (!form) return;
  form.addEventListener('submit', handleFormSubmit);
}

function showNotification(message, type = 'success') {
  const COLORS = { success: '#10b981', error: '#ef4444' };

  const el = document.createElement('div');
  el.setAttribute('role', 'alert');
  el.setAttribute('aria-live', 'polite');

  Object.assign(el.style, {
    position:        'fixed',
    top:             '5rem',
    right:           '1rem',
    padding:         '1rem 1.5rem',
    backgroundColor: COLORS[type] ?? COLORS.success,
    color:           'white',
    borderRadius:    '0.5rem',
    boxShadow:       '0 10px 15px -3px rgba(0,0,0,0.15)',
    zIndex:          '9999',
    fontSize:        '0.9rem',
    fontFamily:      "'DM Sans', sans-serif",
    maxWidth:        '320px',
    cursor:          'pointer',
    animation:       'cpSlideIn 0.3s ease-out forwards',
  });

  el.textContent = message;
  document.body.appendChild(el);

  // Auto-eliminar tras 3 segundos con animación de salida
  const timer = setTimeout(() => {
    el.style.animation = 'cpSlideOut 0.3s ease-out forwards';
    el.addEventListener('animationend', () => el.remove(), { once: true });
  }, 3000);

  // Click para cerrar manualmente
  el.addEventListener('click', () => { clearTimeout(timer); el.remove(); });
}

function injectNotificationStyles() {
  if (document.getElementById('cp-notification-styles')) return;

  const style = document.createElement('style');
  style.id = 'cp-notification-styles';
  style.textContent = `
    @keyframes cpSlideIn {
      from { transform: translateX(110%); opacity: 0; }
      to   { transform: translateX(0);   opacity: 1; }
    }
    @keyframes cpSlideOut {
      from { transform: translateX(0);   opacity: 1; }
      to   { transform: translateX(110%); opacity: 0; }
    }
  `;
  document.head.appendChild(style);
}

function init() {
  injectNotificationStyles(); // keyframes disponibles antes de cualquier notificación
  initNavScroll();            // fondo del navbar según posición de scroll
  initMobileMenu();           // toggle hamburguesa + cierre automático
  initSoftwareButtons();      // botones "Acceder al Software"
  initSmoothScroll();         // scroll suave en enlaces ancla
  initContactForm();          // validación y envío del formulario

  if (typeof window.renderServices === 'function') {
    window.renderServices();
  } else {
    console.warn('[main.js] renderServices no está disponible. ¿Cargaste services.js primero?');
  }
}

document.addEventListener('DOMContentLoaded', init);