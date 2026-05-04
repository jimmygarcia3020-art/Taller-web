document.addEventListener("DOMContentLoaded", () => {
  const formEgreso = document.getElementById("formEgreso");
  const tablaEgresosBody = document.getElementById("tablaEgresosBody");

  // Calcular IGV automáticamente
  formEgreso.addEventListener("input", () => {
    const base = parseFloat(document.getElementById("baseImponible").value) || 0;
    const igv = base * 0.18;
    const total = base + igv;
    document.getElementById("igv").value = igv.toFixed(2);
    document.getElementById("total").value = total.toFixed(2);
  });

  // Enviar datos al PHP + agregar fila
  formEgreso.addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita recarga de la página
    
    // CORRECCIÓN: Intentamos obtener el ID del cliente seleccionado (puede estar en un input oculto o localStorage)
    const idClienteDOM = document.getElementById("id_cliente")?.value;
    const idClienteStorage = localStorage.getItem("cliente_activo_id") || "";

    // Captura de datos estructurada para coincidir con la BD
    const datos = {
      fecha: document.getElementById("fecha").value,
      doc: document.getElementById("tipoComprobante").value,
      entidad: document.getElementById("rucProveedor").value, // Proveedor
      descripcion: document.getElementById("descripcion").value,
      monto: parseFloat(document.getElementById("total").value),
      id_cliente: idClienteDOM || idClienteStorage,
      razon: document.getElementById("rucProveedor").value // Fallback de búsqueda para el PHP
    };

    try {
      // ENVÍO AL PHP
      const res = await fetch("egresos_contador.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
      });

      const respuesta = await res.json();

      if (respuesta.status === "success") {
        alert("✅ Egreso registrado correctamente.");
        
        // AGREGAR FILA A LA TABLA DINÁMICAMENTE
        const fila = document.createElement("tr");
        fila.innerHTML = `
          <td>${datos.fecha}</td>
          <td>${datos.doc}</td>
          <td>${datos.entidad}</td>
          <td>${datos.descripcion}</td>
          <td style="color: #e74c3c; font-weight: bold;">- S/ ${datos.monto.toFixed(2)}</td>
        `;
        
        if (tablaEgresosBody) {
          tablaEgresosBody.appendChild(fila);
        }
        
        formEgreso.reset(); // Limpiar formulario
      } else {
        alert("❌ Error: " + respuesta.message);
      }
    } catch (error) {
      console.error("Error en la petición:", error);
      alert("❌ Ocurrió un error al intentar conectar con el servidor.");
    }
  });
});