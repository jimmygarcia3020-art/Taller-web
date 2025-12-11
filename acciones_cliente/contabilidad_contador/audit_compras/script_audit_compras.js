// El contenido de script_audit_compras.js se mantiene IGUAL ya que el filtro
// trabaja con los valores 'factura' y 'notacredito' que ahora están en el HTML.

// ... (Lógica del sidebar) ...
// ... (Lógica del Toggle "Seleccionar Todos") ...

// ===========================================
// Lógica de Filtrado (Botón Ejecutar) - SE MANTIENE IGUAL
// ===========================================
const btnEjecutar = document.querySelector('.btn-ejecutar');
const tablaComprastbody = document.querySelector('#tabla-compras tbody');
const filas = tablaComprastbody ? tablaComprastbody.querySelectorAll('tr') : [];

if (btnEjecutar) {
    btnEjecutar.addEventListener('click', function() {
        const validezFiltro = document.getElementById('filtro-validez').value;
        const comprobanteFiltro = document.getElementById('filtro-comprobante').value; // Ahora solo contendrá 'factura' o 'notacredito'
        const busquedaTermino = document.getElementById('input-buscador').value.toLowerCase().trim();

        let resultadosEncontrados = 0;

        filas.forEach(fila => {
            const validez = fila.getAttribute('data-validez');
            const comprobante = fila.getAttribute('data-comprobante');
            const proveedor = fila.getAttribute('data-proveedor').toLowerCase();
            const documento = fila.getAttribute('data-documento').toLowerCase();

            // 1. FILTRO DE VALIDEZ
            const matchValidez = validezFiltro === 'todos' || validez === validezFiltro;

            // 2. FILTRO DE COMPROBANTE
            const matchComprobante = comprobanteFiltro === 'todos' || comprobante === comprobanteFiltro;

            // 3. FILTRO DE BÚSQUEDA
            const matchBusqueda = busquedaTermino === '' || 
                                  proveedor.includes(busquedaTermino) ||
                                  documento.includes(busquedaTermino);

            // Mostrar/Ocultar la fila
            if (matchValidez && matchComprobante && matchBusqueda) {
                fila.style.display = ''; // Mostrar
                resultadosEncontrados++;
            } else {
                fila.style.display = 'none'; // Ocultar
            }
        });

        console.log(`Filtros aplicados. Se encontraron ${resultadosEncontrados} resultados.`);
    });
}

// ... (Lógica de Botones de Opciones - SE MANTIENE IGUAL) ...