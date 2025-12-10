/**
 * Services Data and Rendering
 * Manages the services section content dynamically
 */

const servicesData = [
  {
    id: 'contabilidad',
    title: 'Contabilidad General',
    description: 'Llevamos la contabilidad completa de tu empresa, desde el registro de operaciones hasta la elaboración de estados financieros.',
    icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
    features: [
      'Registro de operaciones diarias',
      'Estados financieros mensuales',
      'Conciliaciones bancarias',
      'Control de inventarios'
    ]
  },
  {
    id: 'tributacion',
    title: 'Tributación',
    description: 'Asesoramiento integral en materia tributaria para optimizar la carga fiscal de tu empresa de manera legal.',
    icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
    features: [
      'Declaraciones mensuales y anuales',
      'Planificación tributaria',
      'Asesoría en fiscalizaciones',
      'Recursos y apelaciones'
    ]
  },
  {
    id: 'planillas',
    title: 'Planillas y RRHH',
    description: 'Gestión completa de planillas de sueldos y administración de recursos humanos para tu empresa.',
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    features: [
      'Cálculo de planillas mensuales',
      'Liquidaciones de beneficios',
      'Declaraciones ante SUNAT',
      'Asesoría laboral'
    ]
  },
  {
    id: 'auditoria',
    title: 'Auditoría Financiera',
    description: 'Auditorías independientes para verificar la razonabilidad de tus estados financieros y mejorar controles internos.',
    icon: 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    features: [
      'Auditoría de estados financieros',
      'Revisión de controles internos',
      'Auditorías especiales',
      'Informes de gestión'
    ]
  },
  {
    id: 'asesoria',
    title: 'Asesoría Financiera',
    description: 'Consultoría estratégica para la toma de decisiones financieras y el crecimiento sostenible de tu negocio.',
    icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
    features: [
      'Análisis financiero',
      'Proyecciones y presupuestos',
      'Evaluación de inversiones',
      'Reestructuración financiera'
    ]
  },
  {
    id: 'formalizacion',
    title: 'Formalización de Empresas',
    description: 'Te acompañamos en todo el proceso de constitución y formalización de tu empresa desde el inicio.',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    features: [
      'Constitución de empresas',
      'Trámites ante SUNAT',
      'Licencias municipales',
      'Asesoría en régimen tributario'
    ]
  }
];

/**
 * Create service card HTML
 * @param {Object} service - Service data object
 * @returns {string} - HTML string
 */
function createServiceCard(service) {
  const featuresHTML = service.features
    .map(feature => `<li>• ${feature}</li>`)
    .join('');
  
  return `
    <div class="service__card" data-service="${service.id}">
      <div class="service__icon">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${service.icon}"></path>
        </svg>
      </div>
      <h3 class="service__title">${service.title}</h3>
      <p class="service__desc">${service.description}</p>
      <ul class="service__list">
        ${featuresHTML}
      </ul>
    </div>
  `;
}

/**
 * Render all services
 */
function renderServices() {
  const servicesGrid = document.getElementById('servicesGrid');
  
  if (!servicesGrid) {
    console.warn('Services grid element not found');
    return;
  }
  
  const servicesHTML = servicesData
    .map(service => createServiceCard(service))
    .join('');
  
  servicesGrid.innerHTML = servicesHTML;
}

// Initialize services when DOM is ready
document.addEventListener('DOMContentLoaded', renderServices);

// Export for use in other modules if needed
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { servicesData, renderServices };
}