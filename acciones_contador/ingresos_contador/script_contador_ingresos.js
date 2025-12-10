// Script del módulo de ingresos
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-ingreso");
  const tabla = document.querySelector("#tabla-ingresos tbody");
  const montoInput = document.getElementById("monto");
  const igvInput = document.getElementById("igv");

  // Calcular IGV automáticamente
  montoInput.addEventListener("input", () => {
    const monto = parseFloat(montoInput.value) || 0;
    const igv = monto * 0.18;
    igvInput.value = igv.toFixed(2);
  });

  // Registrar comprobante
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const tipo = document.getElementById("tipo").value;
    const serie = document.getElementById("serie").value;
    const numero = document.getElementById("numero").value;
    const fecha = document.getElementById("fecha").value;
    const cliente = document.getElementById("cliente").value;
    const ruc = document.getElementById("ruc").value;
    const monto = parseFloat(montoInput.value).toFixed(2);
    const igv = parseFloat(igvInput.value).toFixed(2);

    // Crear fila visual
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${tipo}</td>
      <td>${serie}</td>
      <td>${numero}</td>
      <td>${fecha}</td>
      <td>${cliente}</td>
      <td>${ruc}</td>
      <td>S/ ${monto}</td>
      <td>S/ ${igv}</td>
    `;

    tabla.appendChild(fila);

    const data = {
      tipo: tipo,
      serie: serie,
      numero: numero,
      fecha: fecha,
      cliente: cliente,
      descripcion: "Registro desde ingresos_contador",
      monto: monto
    };

    fetch("ingresos_contador.php", { 
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    })
      .then(res => res.json())
      .then(response => {
        if (response.status === "success") {
          console.log("✔ Registro guardado en MySQL");
        } else {
          console.error("❌ Error al guardar:", response.message);
        }
      })
      .catch(err => console.error("Error en fetch:", err));

    form.reset();
    igvInput.value = "";
  });
});
