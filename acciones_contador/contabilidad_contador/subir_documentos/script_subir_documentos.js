document.addEventListener('DOMContentLoaded', function() {
    // ===========================================
    // 1. Lógica del MODAL SIRE
    // ===========================================
    const modal = document.getElementById('sire-modal');
    const openBtn = document.getElementById('open-sire-modal');
    const closeBtn = document.getElementById('close-sire-modal');
    const closeModalBtn = document.querySelector('.modal-content .close-btn');

    // Abrir el modal
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            modal.style.display = 'block';
        });
    }

    // Cerrar el modal con el botón "Cerrar" del footer
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Cerrar el modal con el icono 'x'
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Cerrar el modal si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });


    // ===========================================
    // 2. Lógica del Carga de Archivos
    // ===========================================
    const dropArea = document.querySelector('.drop-area');
    const fileInput = document.getElementById('file-input');
    const selectFilesBtn = document.getElementById('select-files-btn');
    const filePathDisplay = document.querySelector('.file-path-display');

    // 2.1. Abrir el selector de archivos al hacer clic en "Examinar..."
    if (selectFilesBtn && fileInput) {
        selectFilesBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // 2.2. Abrir el selector de archivos al hacer clic en el área de drop
    if (dropArea && fileInput) {
        dropArea.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // 2.3. Manejar la selección de archivos
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
    }

    // 2.4. Manejar Drag & Drop
    if (dropArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        // Resaltar área cuando se arrastra un archivo
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
        });

        // Manejar los archivos soltados
        dropArea.addEventListener('drop', handleDrop, false);
    }

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDrop(e) {
        let dt = e.dataTransfer;
        let files = dt.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        if (files.length > 0) {
            // Mostrar los nombres de los archivos en el input de texto
            let fileNames = Array.from(files).map(file => file.name);
            filePathDisplay.value = `${fileNames.length} archivo(s) seleccionado(s).`;
            
            // Aquí iría la lógica para procesar o subir los archivos XML
            console.log("Archivos listos para subir:", files);
        } else {
            filePathDisplay.value = "Seleccionar archivos...";
        }
    }

    // ===========================================
    // 3. Lógica de Toggles (ON/OFF)
    // ===========================================
    const toggleCompras = document.getElementById('toggle-compras');
    const toggleVentas = document.getElementById('toggle-ventas');

    // Función para actualizar el texto ON/OFF
    function updateToggleText(checkbox, textElement) {
        textElement.textContent = checkbox.checked ? 'ON' : 'OFF';
    }

    // En el modal, actualizar al cargar
    const textCompras = document.querySelector('#toggle-compras + .slider + .toggle-text');
    const textVentas = document.querySelector('#toggle-ventas + .slider + .toggle-text');
    
    if (toggleCompras && textCompras) {
        updateToggleText(toggleCompras, textCompras);
        toggleCompras.addEventListener('change', () => updateToggleText(toggleCompras, textCompras));
    }
    
    if (toggleVentas && textVentas) {
        updateToggleText(toggleVentas, textVentas);
        toggleVentas.addEventListener('change', () => updateToggleText(toggleVentas, textVentas));
    }

    // ===========================================
    // 4. Lógica de Extracción de Datos (Simulada)
    // ===========================================
    const btnExtraerDatos = document.querySelector('.btn-extraer-datos');

    if (btnExtraerDatos) {
        btnExtraerDatos.addEventListener('click', function() {
            const periodo = document.getElementById('periodo-select').value;
            const extraerCompras = toggleCompras.checked;
            const extraerVentas = toggleVentas.checked;

            if (!periodo) {
                alert('Por favor, seleccione un periodo a extraer.');
                return;
            }

            // Simulación de la llamada a la API de SUNAT-SIRE
            console.log(`Iniciando extracción de datos SIRE para el periodo: ${periodo}`);
            console.log(`Extraer Compras: ${extraerCompras}`);
            console.log(`Extraer Ventas: ${extraerVentas}`);

            alert(`Solicitud enviada para extraer datos del SIRE del periodo ${periodo}.\nCompras: ${extraerCompras ? 'Sí' : 'No'}, Ventas: ${extraerVentas ? 'Sí' : 'No'}.`);
            
            // Cierra el modal después de la acción (puedes cambiar esto si la extracción es asíncrona)
            modal.style.display = 'none';
        });
    }

});

