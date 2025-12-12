document.addEventListener("DOMContentLoaded", () => {
  const tipoSelect = document.getElementById("tipo");
  const fechaInicio = document.getElementById("fechaInicio");
  const fechaFin = document.getElementById("fechaFin");
  const tbody = document.querySelector("#tablaReportes tbody");
  const totalSpan = document.getElementById("total");
  const btnFiltrar = document.getElementById("filtrar");

  if (!tbody || !totalSpan) {
    console.error("ERROR: #tablaReportes tbody o #total no encontrados en el DOM.");
    return;
  }

    function renderRow(item) {
    const tr = document.createElement("tr");

    // checkbox
    const tdChk = document.createElement("td");
    const chk = document.createElement("input");
    chk.type = "checkbox";
    chk.className = "fila";
    chk.dataset.id = item.id || "";
    tdChk.appendChild(chk);
    tr.appendChild(tdChk);

    // columnas de datos
    const tdFecha = document.createElement("td"); tdFecha.textContent = item.fecha || ""; tr.appendChild(tdFecha);
    const tdDoc   = document.createElement("td"); tdDoc.textContent = item.doc || ""; tr.appendChild(tdDoc);
    const tdEnt   = document.createElement("td"); tdEnt.textContent = item.entidad || ""; tr.appendChild(tdEnt);
    const tdDesc  = document.createElement("td"); tdDesc.textContent = item.descripcion || ""; tr.appendChild(tdDesc);
    const tdMonto = document.createElement("td"); tdMonto.textContent = `S/ ${(parseFloat(item.monto)||0).toFixed(2)}`; tr.appendChild(tdMonto);

    // acciones
    const tdAcc = document.createElement("td");
    const btnEdit = document.createElement("button");
    btnEdit.type = "button";
    // mantenemos la clase nueva y la clase legacy para recuperar estilos previos
    btnEdit.className = "btn-editar btn-edit";
    btnEdit.textContent = "Editar";
    btnEdit.title = "Editar registro";
    // enlazamos funcionalidad (usa la función global editar)
    btnEdit.addEventListener("click", (ev) => {
      ev.preventDefault();
      // pasar tipo actual si existe
      const tipo = (typeof tipoSelect !== "undefined" && tipoSelect) ? tipoSelect.value : "";
      // item.id puede ser number o string
      editar(item.id, tipo);
    });

    const btnDel = document.createElement("button");
    btnDel.type = "button";
    btnDel.className = "btn-eliminar btn-delete";
    btnDel.textContent = "Eliminar";
    btnDel.title = "Eliminar registro";
    // enlazamos funcionalidad (usa la función global eliminar)
    btnDel.addEventListener("click", (ev) => {
      ev.preventDefault();
      const tipo = (typeof tipoSelect !== "undefined" && tipoSelect) ? tipoSelect.value : "";
      eliminar(item.id, tipo);
    });

    const wrap = document.createElement("div");
    wrap.style.marginTop = "6px";
    wrap.appendChild(btnEdit);
    wrap.appendChild(btnDel);

    tdAcc.appendChild(wrap);
    tr.appendChild(tdAcc);

    return tr;
  }


  async function cargarDatos() {
    const tipo = tipoSelect ? tipoSelect.value : "";
    const desde = fechaInicio ? fechaInicio.value : "";
    const hasta = fechaFin ? fechaFin.value : "";

    // obtener cliente id desde global o input dataset
    let clienteId = null;
    if (typeof window.clienteSeleccionadoId !== "undefined" && window.clienteSeleccionadoId) clienteId = String(window.clienteSeleccionadoId);
    else {
      const clienteFiltro = document.getElementById("clienteFiltro");
      if (clienteFiltro && clienteFiltro.dataset && clienteFiltro.dataset.id) clienteId = String(clienteFiltro.dataset.id);
    }

    if (!clienteId) {
      tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:12px;">Seleccione un cliente antes de filtrar.</td></tr>`;
      totalSpan.textContent = "S/ 0.00";
      console.warn("[reportes] No hay cliente seleccionado.");
      return;
    }

    const params = new URLSearchParams();
    params.set("cliente_id", clienteId); // preferido por tus últimos php
    params.set("id_cliente", clienteId); // fallback por compatibilidad
    if (tipo) params.set("tipo", tipo);
    if (desde) params.set("desde", desde);
    if (hasta) params.set("hasta", hasta);
    params.set("t", Date.now());

    const url = `reportes_clientes.php?${params.toString()}`;
    console.log("[reportes] fetch ->", url);

    try {
      const resp = await fetch(url, { cache: "no-store" });
      const status = resp.status;
      const text = await resp.text(); // leemos texto primero para depuración

      // si la respuesta no es JSON válido vamos a mostrar el texto para que lo revises
      let json = null;
      try {
        json = JSON.parse(text);
      } catch (parseErr) {
        console.error("[reportes] respuesta NO-JSON del servidor. HTTP", status);
        console.groupCollapsed(">>> respuesta cruda del servidor (abrir)");
        console.log(text);
        console.groupEnd();
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:left;padding:12px;color:#900;"><strong>Respuesta inválida del servidor (ver consola).</strong><pre style="white-space:pre-wrap;">${escapeHtml(text)}</pre></td></tr>`;
        totalSpan.textContent = "S/ 0.00";
        return;
      }

      // si llegamos aquí json es válido
      let rows = [];
      let total = 0;
      if (Array.isArray(json)) {
        rows = json;
        total = rows.reduce((s,r)=> s + (parseFloat(r.monto)||0), 0);
      } else if (json && Array.isArray(json.data)) {
        rows = json.data;
        total = typeof json.total !== "undefined" ? parseFloat(json.total||0) : rows.reduce((s,r)=> s + (parseFloat(r.monto)||0), 0);
      } else {
        console.warn("[reportes] JSON pero sin formato esperado:", json);
        rows = Array.isArray(json) ? json : [];
        total = rows.reduce((s,r)=> s + (parseFloat(r.monto)||0), 0);
      }

      tbody.innerHTML = "";
      if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:12px;">No se encontraron registros para este cliente.</td></tr>`;
      } else {
        rows.forEach(item => tbody.appendChild(renderRow(item)));
      }
      totalSpan.textContent = `S/ ${Number(total).toFixed(2)}`;

    } catch (err) {
      console.error("ERROR fetch/cargarDatos:", err);
      tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:12px;">Error al conectar con el servidor. Revisa consola (Network) y la ruta.</td></tr>`;
      totalSpan.textContent = "S/ 0.00";
    }
  }

  // helper para escapar HTML al mostrar texto crudo
  function escapeHtml(s) {
    if (!s) return "";
    return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
  }

  // cargar al inicio (si cliente no existe mostrará mensaje)
  cargarDatos();

  if (btnFiltrar) btnFiltrar.addEventListener("click", cargarDatos);

  // botón agregar
  const btnAgregar = document.getElementById("btnAgregar");
  if (btnAgregar) {
    btnAgregar.addEventListener("click", () => {
      const tipoAdd = prompt("¿Qué desea agregar? (ventas/compras)");
      if (!tipoAdd) return;
      const t = tipoAdd.toLowerCase().trim();
      if (t === "ventas") window.location.href = "../ingresos_contador/ingresos_contador.html";
      else if (t === "compras") window.location.href = "../egresos_contador/egresos_contador.html";
      else alert("Tipo no válido.");
    });
  }
});

// funciones globales
function eliminar(id, tipo) {
  if (!id) return alert("ID inválido");
  if (confirm("¿Eliminar este registro?")) {
    window.location.href = `reportes_eliminar.php?id=${id}&tipo=${encodeURIComponent(tipo)}`;
  }
}
function editar(id, tipo) {
  alert("Función de edición próximamente.");
}


