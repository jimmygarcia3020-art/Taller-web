
const servicesData = [
  {
    id: 'contabilidad',
    title: 'Contabilidad General',
    description: 'Registro y control de operaciones contables según las NIC y normativa peruana vigente.',
    icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z',
    features: [
      'Libros contables electrónicos',
      'Estados financieros mensuales',
      'Análisis de cuentas',
      'Conciliaciones bancarias',
    ],
  },
  {
    id: 'tributacion',
    title: 'Asesoría Tributaria',
    description: 'Planificación y cumplimiento tributario ante SUNAT, minimizando la carga fiscal de manera legal.',
    icon: 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
    features: [
      'Declaraciones mensuales',
      'PDT 621 / 601',
      'Planeamiento tributario',
      'Recursos de reclamación',
    ],
  },
  {
    id: 'auditoria',
    title: 'Auditoría Financiera',
    description: 'Revisión independiente de estados financieros para garantizar transparencia e integridad.',
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    features: [
      'Auditoría de estados financieros',
      'Control interno',
      'Informe de auditor',
      'Due diligence',
    ],
  },
  {
    id: 'planillas',
    title: 'Planillas y RRHH',
    description: 'Gestión completa de planillas, beneficios sociales y cumplimiento laboral.',
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    features: [
      'Liquidaciones de beneficios',
      'AFP y EsSalud',
      'PLAME mensual',
      'Contratos laborales',
    ],
  },
  {
    id: 'consultoria',
    title: 'Consultoría Empresarial',
    description: 'Asesoría estratégica para la toma de decisiones financieras y el crecimiento sostenible.',
    icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
    features: [
      'Análisis financiero',
      'Valorización de empresas',
      'Presupuestos y proyecciones',
      'Reestructuración financiera',
    ],
  },
  {
    id: 'formalizacion',
    title: 'Constitución de Empresas',
    description: 'Acompañamiento completo en la formalización y puesta en marcha de tu negocio.',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    features: [
      'Inscripción en SUNARP',
      'RUC y régimen tributario',
      'Licencias y permisos',
      'Asesoría de régimen',
    ],
  },
];

function createServiceCard(service) {
  // Genera un <div> por cada feature con su checkmark visual
  const featuresHTML = service.features
    .map((feature) => `<div class="service__list-item">${feature}</div>`)
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
      <div class="service__list">
        ${featuresHTML}
      </div>
    </div>
  `;
}

function renderServices() {
  const grid = document.getElementById('servicesGrid');

  if (!grid) {
    console.warn('[services.js] No se encontró el elemento #servicesGrid.');
    return;
  }

  // Genera todas las tarjetas y las inserta en el DOM de una sola vez
  grid.innerHTML = servicesData.map(createServiceCard).join('');
}

// Expone renderServices globalmente para que main.js pueda invocarlo
window.renderServices = renderServices;