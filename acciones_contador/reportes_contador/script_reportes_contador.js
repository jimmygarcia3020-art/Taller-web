document.addEventListener("DOMContentLoaded", () => {
    const tipoSelect = document.getElementById("tipo");
    const fechaInicio = document.getElementById("fechaInicio");
    const fechaFin = document.getElementById("fechaFin");
    const tbody = document.querySelector("#tablaReportes tbody");
    const totalSpan = document.getElementById("total");
    const btnFiltrar = document.getElementById("filtrar");
    const btnActualizar = document.getElementById("btnActualizar");

    // sanity checks
    console.log("DEBUG: DOM cargado. elementos:", {
      tipo: !!tipoSelect,
      fechaInicio: !!fechaInicio,
      fechaFin: !!fechaFin,
      tbody: !!tbody,
      total: !!totalSpan,
      filtrar: !!btnFiltrar,
      actualizar: !!btnActualizar
    });

    // si faltan elementos críticos, mostrar error y salir
    if (!tbody || !totalSpan) {
      console.error("ERROR: #tablaReportes tbody o #total no encontrados en el DOM.");
      return;
    }

    // funcionalidad principal
    function cargarDatos() {
        const tipo = tipoSelect ? tipoSelect.value : "";
        const inicio = fechaInicio ? fechaInicio.value : "";
        const fin = fechaFin ? fechaFin.value : "";

        const url = `reportes_contador.php?tipo=${encodeURIComponent(tipo)}&inicio=${encodeURIComponent(inicio)}&fin=${encodeURIComponent(fin)}&t=${Date.now()}`;

        console.log("DEBUG: solicitando datos a:", url);

        fetch(url, { cache: "no-store" }) // no-store para evitar caché
            .then(res => {
                if (!res.ok) throw new Error("Respuesta HTTP " + res.status);
                return res.json();
            })
            .then(data => {
                console.log("DEBUG: datos recibidos:", data);
                tbody.innerHTML = "";
                let total = 0;

                if (!Array.isArray(data)) {
                    console.error("ERROR: la respuesta no es un array JSON. Comprueba reportes_contador.php");
                    return;
                }

                data.forEach(item => {
                    total += parseFloat(item.monto || 0);

                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td><input type="checkbox" class="fila" data-id="${item.id}"></td>
                        <td>${item.fecha}</td>
                        <td>${item.doc}</td>
                        <td>${item.entidad}</td>
                        <td>${item.descripcion}</td>
                        <td>S/ ${parseFloat(item.monto).toFixed(2)}</td>
                        <td>
                            <button class="btn-edit" onclick="editar(${item.id}, '${tipo}')">Editar</button>
                            <button class="btn-delete" onclick="eliminar(${item.id}, '${tipo}')">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                totalSpan.textContent = `S/ ${total.toFixed(2)}`;
            })
            .catch(err => {
                console.error("ERROR fetch/cargarDatos:", err);
            });
    }

    // cargar datos al inicio
    cargarDatos();

    // listeners (si el elemento existe)
    if (btnFiltrar) btnFiltrar.addEventListener("click", cargarDatos);
    if (btnActualizar) {
      btnActualizar.addEventListener("click", () => {
        console.log("DEBUG: btnActualizar clickeado");
        cargarDatos();
      });
      // por seguridad, también soportamos "enter" o submit si el botón está dentro de un form
      btnActualizar.addEventListener("keydown", (ev) => {
        if (ev.key === "Enter") cargarDatos();
      });
    } else {
      console.warn("WARN: botón #btnActualizar no encontrado. Asegúrate de que el id existe en el HTML.");
    }

    const btnAgregar = document.getElementById("btnAgregar");
    if (btnAgregar) {
      btnAgregar.addEventListener("click", () => {
          const tipo = prompt("¿Qué desea agregar? (ventas/compras)").toLowerCase();
          if (tipo === "ventas") {
              window.location.href = "../ingresos_contador/ingresos_contador.html";
          } else if (tipo === "compras") {
              window.location.href = "../egresos_contador/egresos_contador.html";
          } else {
              alert("Tipo no válido. Escriba 'ventas' o 'compras'.");
          }
      });
    }
});

// funciones globales que usas en los botones de cada fila
function eliminar(id, tipo) {
    if (confirm("¿Eliminar este registro?")) {
        window.location.href = `reportes_eliminar.php?id=${id}&tipo=${tipo}`;
    }
}

function editar(id, tipo) {
    alert("Función de edición próximamente.");
}
