document.addEventListener('DOMContentLoaded', function() {
    // ... (Lógica del sidebar y Toggle "Seleccionar Todos" - SE MANTIENE IGUAL) ...
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }

    const toggleSelectAll = document.getElementById('toggle-select-all');
    
    if (toggleSelectAll) {
        toggleSelectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            
            document.querySelectorAll('#tabla-ventas tbody tr').forEach(row => {
                const contabilizarCheckbox = row.querySelector('td:nth-child(9) input[type="checkbox"]');
                if (contabilizarCheckbox) {
                    contabilizarCheckbox.checked = isChecked;
                }
            });

            const toggleText = document.querySelector('.select-all-toggle .toggle-text-small');
            if (toggleText) {
                toggleText.textContent = isChecked ? 'SÍ' : 'NO';
            }
        });
        
        const toggleText = document.querySelector('.select-all-toggle .toggle-text-small');
        if (toggleText) {
            toggleText.textContent = toggleSelectAll.checked ? 'SÍ' : 'NO';
        }
    }


    // ===========================================
    // Lógica de Filtrado (Botón Ejecutar)
    // ===========================================
    const btnEjecutar = document.querySelector('.btn-ejecutar');
    const tablaVentastbody = document.querySelector('#tabla-ventas tbody');
    const filas = tablaVentastbody ? tablaVentastbody.querySelectorAll('tr') : [];

    if (btnEjecutar) {
        btnEjecutar.addEventListener('click', function() {
            const validezFiltro = document.getElementById('filtro-validez').value;
            const comprobanteFiltro = document.getElementById('filtro-comprobante').value;
            const busquedaTermino = document.getElementById('input-buscador').value.toLowerCase().trim();

            let resultadosEncontrados = 0;

            filas.forEach(fila => {
                const validez = fila.getAttribute('data-validez');
                const comprobante = fila.getAttribute('data-comprobante');
                const cliente = fila.getAttribute('data-cliente').toLowerCase(); 
                const documento = fila.getAttribute('data-documento').toLowerCase();

                // 1. FILTRO DE VALIDEZ
                const matchValidez = validezFiltro === 'todos' || validez === validezFiltro;

                // 2. FILTRO DE COMPROBANTE
                const matchComprobante = comprobanteFiltro === 'todos' || comprobante === comprobanteFiltro;

                // 3. FILTRO DE BÚSQUEDA (Cliente/RUC o Nro. Documento)
                const matchBusqueda = busquedaTermino === '' || 
                                      cliente.includes(busquedaTermino) ||
                                      documento.includes(busquedaTermino);

                // Mostrar/Ocultar la fila
                if (matchValidez && matchComprobante && matchBusqueda) {
                    fila.style.display = ''; // Mostrar
                    resultadosEncontrados++;
                } else {
                    fila.style.display = 'none'; // Ocultar
                }
            });

            console.log(`Filtros aplicados. Se encontraron ${resultadosEncontrados} resultados de Ventas.`);
            // LA SIGUIENTE LÍNEA HA SIDO ELIMINADA:
            // alert(`Filtros aplicados. Se encontraron ${resultadosEncontrados} documentos de Venta.`); 
            // ¡El filtro ahora aplica los cambios de forma silenciosa!
        });
    }

    // ... (Lógica de Botones de Opciones - SE MANTIENE IGUAL) ...
    if (tablaVentastbody) {
        tablaVentastbody.addEventListener('click', function(event) {
            const target = event.target;
            
            if (target.classList.contains('btn-option')) {
                const fila = target.closest('tr');
                const nroDocumento = fila.getAttribute('data-documento');
                const cliente = fila.getAttribute('data-cliente');

                if (target.classList.contains('blue')) {
                    alert(`📝 EDITAR Documento de Venta:\nNúmero: ${nroDocumento}\nCliente: ${cliente}\n\n[SIMULACIÓN] Aquí se abriría un modal para modificar los datos del RVE.`);
                
                } else if (target.classList.contains('red')) {
                    
                    if (confirm(`❌ ¿Está seguro que desea ELIMINAR la Venta con Nro. Documento ${nroDocumento} a ${cliente}?\n\nEsta acción no se puede deshacer.`)) {
                        
                        fila.remove();
                        alert(`✅ Documento de Venta ${nroDocumento} ELIMINADO correctamente.`);
                    }
                }
            }
        });
    }

});