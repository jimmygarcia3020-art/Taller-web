// Script del módulo de ingresos
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-ingreso");
  const tabla = document.querySelector("#tabla-ingresos tbody");
  const montoInput = document.getElementById("monto");
  const igvInput = document.getElementById("igv");

  // Calcular IGV automáticamente
  if (montoInput && igvInput) {
    montoInput.addEventListener("input", () => {
      const monto = parseFloat(montoInput.value) || 0;
      const igv = monto * 0.18;
      igvInput.value = igv.toFixed(2);
    });
  }

  // Registrar comprobante
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const tipo = document.getElementById("tipo").value;
    const serie = document.getElementById("serie").value;
    const numero = document.getElementById("numero").value;
    const fecha = document.getElementById("fecha").value;
    const cliente = document.getElementById("cliente").value; // Nombre del comprador
    const ruc = document.getElementById("ruc").value;
    const monto = parseFloat(montoInput.value).toFixed(2);
    const igv = parseFloat(igvInput.value).toFixed(2);

    // CORRECCIÓN: Obtener cliente activo
    const idClienteDOM = document.getElementById("id_cliente")?.value;
    const idClienteStorage = localStorage.getItem("cliente_activo_id") || "";

    const data = {
      tipo: tipo,
      serie: serie,
      numero: numero,
      fecha: fecha,
      cliente: cliente, 
      descripcion: document.getElementById("descripcion")?.value || ruc, // Soporte por si existe campo descripción
      monto: monto,
      id_cliente: idClienteDOM || idClienteStorage,
      nombre_cliente: cliente // Fallback para PHP
    };

    try {
      const res = await fetch("ingresos_contador.php", { 
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const response = await res.json();

      if (response.status === "success") {
        // Crear fila visual
        const fila = document.createElement("tr");
        fila.innerHTML = `
          <td>${tipo}</td>
          <td>${serie}</td>
          <td>${numero}</td>
          <td>${fecha}</td>
          <td>${cliente}</td>
          <td>${ruc}</td>
          <td style="color: #27ae60; font-weight: bold;">S/ ${monto}</td>
          <td>S/ ${igv}</td>
        `;

        if (tabla) tabla.appendChild(fila);
        form.reset();
        alert("✅ Venta registrada con éxito.");
      } else {
        alert("❌ Error: " + response.message);
      }
    } catch (error) {
      console.error("Error en petición:", error);
      alert("❌ Ocurrió un problema de red.");
    }
  });
});