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
formEgreso.addEventListener("submit", (e) => {
  e.preventDefault(); // evita recarga
  
  // Captura de datos
  const datos = {
    fecha: document.getElementById("fecha").value,
    doc: document.getElementById("tipoComprobante").value,
    entidad: document.getElementById("rucProveedor").value,
    descripcion: document.getElementById("descripcion").value,
    monto: parseFloat(document.getElementById("total").value)
  };

  // ENVÍO AL PHP
  fetch("egresos_contador.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos)
  })
  .then(res => res.json())
  .then(respuesta => {
    if (respuesta.status === "success") {

      // AGREGAR FILA A LA TABLA
      const fila = `
        <tr>
          <td>${datos.fecha}</td>
          <td>${datos.doc}</td>
          <td>${datos.entidad}</td>
          <td>${datos.descripcion}</td>
          <td>${(datos.monto / 1.18).toFixed(2)}</td>
          <td>${(datos.monto - datos.monto / 1.18).toFixed(2)}</td>
          <td>${datos.monto.toFixed(2)}</td>
        </tr>
      `;

      tablaEgresosBody.insertAdjacentHTML("beforeend", fila);
      formEgreso.reset();
    }
  });
});
