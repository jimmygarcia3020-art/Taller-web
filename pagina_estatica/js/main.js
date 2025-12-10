/**
 * Main JavaScript File
 * Handles navigation and form interactions
 */

// Toggle mobile menu
function toggleMenu() {
  const mobileMenu = document.getElementById('mobileMenu');
  mobileMenu.classList.toggle('hidden');
}

// Handle contact form submission
document.addEventListener('DOMContentLoaded', function() {
  const contactForm = document.getElementById('contactForm');
  
  if (contactForm) {
    contactForm.addEventListener('submit', handleFormSubmit);
  }
  
  // Close mobile menu when clicking on a link
  const mobileLinks = document.querySelectorAll('.mobile__link');
  mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
      const mobileMenu = document.getElementById('mobileMenu');
      mobileMenu.classList.add('hidden');
    });
  });
  
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
});

/**
 * Handle contact form submission
 * @param {Event} e - Form submit event
 */
function handleFormSubmit(e) {
  e.preventDefault();
  
  const formData = new FormData(e.target);
  const data = {
    nombre: formData.get('nombre'),
    apellido: formData.get('apellido'),
    email: formData.get('email'),
    mensaje: formData.get('mensaje')
  };
  
  // Validate form data
  if (!validateFormData(data)) {
    showNotification('Por favor, complete todos los campos correctamente.', 'error');
    return;
  }
  
  // Here you would normally send the data to a server
  // For now, we'll just show a success message
  console.log('Form data:', data);
  
  showNotification('¡Gracias por contactarnos! Te responderemos pronto.', 'success');
  e.target.reset();
}

/**
 * Validate form data
 * @param {Object} data - Form data object
 * @returns {boolean} - True if valid
 */
function validateFormData(data) {
  // Check if all fields are filled
  if (!data.nombre || !data.apellido || !data.email || !data.mensaje) {
    return false;
  }
  
  // Validate email format
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(data.email)) {
    return false;
  }
  
  return true;
}

/**
 * Show notification message
 * @param {string} message - Notification message
 * @param {string} type - Notification type (success, error)
 */
function showNotification(message, type = 'success') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `notification notification--${type}`;
  notification.textContent = message;
  
  // Add styles
  Object.assign(notification.style, {
    position: 'fixed',
    top: '5rem',
    right: '1rem',
    padding: '1rem 1.5rem',
    backgroundColor: type === 'success' ? '#10b981' : '#ef4444',
    color: 'white',
    borderRadius: '0.5rem',
    boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
    zIndex: '1000',
    animation: 'slideIn 0.3s ease-out'
  });
  
  // Add to document
  document.body.appendChild(notification);
  
  // Remove after 3 seconds
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease-out';
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(100%);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);