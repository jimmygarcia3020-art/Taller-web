document.addEventListener("DOMContentLoaded", () => {

    // Datos iniciales (puedes cargarlos desde servidor más adelante)
    let registrosImpuestos = [
        { id: 1, periodo: 'Julio 2025', tipo: 'IGV', monto: 450.00, estado: 'No pagado' },
        { id: 2, periodo: 'Junio 2025', tipo: 'Renta', monto: 320.00, estado: 'Pagado' },
        { id: 3, periodo: 'Mayo 2025', tipo: 'IGV', monto: 880.50, estado: 'No pagado' }
    ];
    let nextId = 4;

    // --- ELEMENTOS DEL DOM (declarar primero) ---
    const tablaBody = document.getElementById("tabla-body");
    const nuevoRegistroBtn = document.getElementById("nuevo-registro");
    const calcularBtn = document.getElementById("calcular-btn");
    const totalVentasInput = document.getElementById("total-ventas");
    const totalComprasInput = document.getElementById("total-compras");
    const resultadoIGV = document.getElementById("resultado-igv");
    const infoBtn = document.getElementById("info-btn");
    const infoBox = document.getElementById("info-box");
    const closeInfo = document.getElementById("close-info");

    const modalRegistro = document.getElementById("modal-registro");
    const closeModalBtn = document.querySelector(".close-modal-btn");
    const formNuevoImpuesto = document.getElementById("form-nuevo-impuesto");
    const regPeriodo = document.getElementById("reg-periodo");
    const regTipo = document.getElementById("reg-tipo");
    const regMonto = document.getElementById("reg-monto");

    const modalEliminar = document.getElementById("modal-eliminar");
    const btnCancelarEliminar = document.getElementById("cancelar-eliminar");
    const btnConfirmarEliminar = document.getElementById("confirmar-eliminar");
    let idPendienteEliminar = null;

    // --- FUNCIONES ---
    function cargarTabla() {
        tablaBody.innerHTML = "";
        registrosImpuestos.forEach((registro) => {
            const estadoClass = registro.estado === 'Pagado' ? 'pagado' : 'no-pagado';
            
            const nuevaFila = document.createElement("tr");
            nuevaFila.innerHTML = `
                <td>${registro.periodo}</td>
                <td>${registro.tipo}</td>
                <td>${parseFloat(registro.monto).toFixed(2)}</td>
                <td><span class="estado ${estadoClass}">${registro.estado}</span></td>
                <td>
                    <button class="btn-pago" data-id="${registro.id}">Cambiar Estado</button>
                    <button class="btn-eliminar" data-id="${registro.id}">Eliminar</button>
                </td>
            `;
            tablaBody.appendChild(nuevaFila);
        });
        activarBotones();
    }

    function activarBotones() {
    const botonesPago = document.querySelectorAll(".btn-pago");
    const botonesEliminar = document.querySelectorAll(".btn-eliminar");

    botonesPago.forEach((btn) => {
        btn.onclick = async () => { 
            const id = parseInt(btn.dataset.id);
            if (!id) return;

            // Opcional: indicador visual mientras actualiza
            btn.disabled = true;
            const textoAntes = btn.textContent;
            btn.textContent = 'Actualizando...';

            try {
                // Hacemos POST al endpoint que togglea/actualiza el estado
                const res = await fetch('actualizar_estado.php', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: new URLSearchParams({ id: id })
                });

                const data = await res.json();

                if (res.ok && data.ok) {
                    // Actualizamos el array local con el estado devuelto por el servidor
                    const registro = registrosImpuestos.find(r => r.id === id);
                    if (registro) {
                        registro.estado = data.estado;
                        cargarTabla();
                    } else {
                        // Si no está localmente, podrías recargar desde el servidor; aquí ignoramos
                        console.warn('Registro actualizado en BD pero no existe localmente');
                    }
                } else {
                    alert('Error al actualizar estado: ' + (data.error || 'Respuesta no OK'));
                }
            } catch (err) {
                console.error(err);
                alert('Error de conexión al actualizar estado.');
            } finally {
                btn.disabled = false;
                btn.textContent = textoAntes;
            }
        };
    });

    botonesEliminar.forEach((btn) => {
        btn.onclick = () => { 
            idPendienteEliminar = parseInt(btn.dataset.id);
            modalEliminar.style.display = "flex";
        };
    });
}


    // --- MODAL y UI ---
    nuevoRegistroBtn.addEventListener("click", () => {
        modalRegistro.style.display = "flex";
    });

    closeModalBtn.addEventListener("click", () => {
        modalRegistro.style.display = "none";
        formNuevoImpuesto.reset();
    });

    window.addEventListener("click", (event) => {
        if (event.target === modalRegistro) {
            modalRegistro.style.display = "none";
            formNuevoImpuesto.reset();
        }
        if (event.target === modalEliminar) {
            modalEliminar.style.display = "none";
            idPendienteEliminar = null;
        }
    });

    btnCancelarEliminar.addEventListener("click", () => {
        modalEliminar.style.display = "none";
        idPendienteEliminar = null;
    });

    btnConfirmarEliminar.addEventListener("click", () => {
        if (idPendienteEliminar !== null) {
            registrosImpuestos = registrosImpuestos.filter(r => r.id !== idPendienteEliminar);
            cargarTabla();
        }
        modalEliminar.style.display = "none";
        idPendienteEliminar = null;
    });

    // --- SUBMIT (solo 1 manejador: hace fetch y actualiza UI con la respuesta) ---
    formNuevoImpuesto.addEventListener("submit", async (e) => {
        e.preventDefault();

        const periodo = regPeriodo.value.trim();
        const tipo = regTipo.value;
        const monto = parseFloat(regMonto.value);

        if (!periodo || !tipo || isNaN(monto) || monto <= 0) {
            alert("Complete todos los campos con valores válidos.");
            return;
        }

        const payload = {
            periodo: periodo,
            tipo: tipo,
            monto: monto
        };

        const botonGuardar = document.getElementById("guardar-registro");
        botonGuardar.disabled = true;
        botonGuardar.textContent = "Guardando...";

        try {
            const res = await fetch('guardar_impuesto.php', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: new URLSearchParams(payload)
            });

            const data = await res.json();

            if (res.ok && data.ok) {
                registrosImpuestos.push({
                    id: data.id || (nextId++),
                    periodo: data.periodo,
                    tipo: data.tipo,
                    monto: parseFloat(data.monto),
                    estado: data.estado // viene desde el servidor ("Pagado")
                });
                cargarTabla();

                modalRegistro.style.display = "none";
                formNuevoImpuesto.reset();
            } else {
                alert("Error al guardar: " + (data.error || "Respuesta no OK"));
            }
        } catch (err) {
            console.error(err);
            alert("Error de conexión al guardar.");
        } finally {
            botonGuardar.disabled = false;
            botonGuardar.textContent = "Guardar Registro";
        }
    });

    // --- SIMULADOR IGV ---
    calcularBtn.addEventListener("click", () => {
        const ventas = parseFloat(totalVentasInput.value) || 0;
        const compras = parseFloat(totalComprasInput.value) || 0;
        const igv = (ventas - compras) * 0.18; 

        if (igv > 0) {
            resultadoIGV.innerHTML = `<span style="color: red;">IGV a pagar: S/ ${igv.toFixed(2)}</span>`;
        } else if (igv < 0) {
            resultadoIGV.innerHTML = `<span style="color: green;">Crédito fiscal: S/ ${Math.abs(igv).toFixed(2)}</span>`;
        } else {
            resultadoIGV.innerHTML = `<span style="color: gray;">Sin IGV por pagar ni crédito fiscal</span>`;
        }
    });

    infoBtn.addEventListener("click", () => {
        infoBox.style.display = "flex";
    });

    closeInfo.addEventListener("click", () => {
        infoBox.style.display = "none";
    });

    // Inicializar tabla
    cargarTabla();
});
