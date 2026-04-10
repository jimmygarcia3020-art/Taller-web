<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel Contable - Impuestos</title>
    <link rel="stylesheet" href="../../../publico/estilos/principal.css" />
    <link rel="stylesheet" href="style_impuestos_contador.css" />
  </head>
  <body>
    <nav class="sidebar" id="sidebar">
      <button class="toggle-btn" id="toggle-btn">☰</button>
      <ul>
        <li>
          <a href="../../../vistas/contador/panel_principal.php"
            ><span class="emoji">🏠</span><span class="text">Inicio</span></a
          >
        </li>
        <li>
          <a href="../ingresos/ingresos_contador.php"
            ><span class="emoji">💰</span><span class="text">Ingresos</span></a
          >
        </li>
        <li>
          <a href="../egresos/egresos_contador.php"
            ><span class="emoji">💸</span><span class="text">Egresos</span></a
          >
        </li>
        <li>
          <a href="impuestos_contador.php"
            ><span class="emoji">🧮</span><span class="text">Impuestos</span></a
          >
        </li>
        <li>
          <a href="../reportes/reportes_contador.php"
            ><span class="emoji">📈</span><span class="text">Reportes</span></a
          >
        </li>

        <li>
          <a href="../../../controladores/autenticacion.php?accion=logout"
            ><span class="emoji">🚪</span
            ><span class="text">Cerrar sesión</span></a
          >
        </li>
      </ul>
    </nav>

    <script>
      // === FUNCIONES ESENCIALES PARA EL TOGGLE Y DROPDOWN ===

      // 1. Alternar el Dropdown (Contabilidad/Reportes)
      function toggleDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        if (dropdown) {
          // Cierra todos los demás dropdowns abiertos
          document
            .querySelectorAll(".dropdown.open")
            .forEach((openDropdown) => {
              if (openDropdown.id !== dropdownId) {
                openDropdown.classList.remove("open");
              }
            });

          // Abre o cierra el dropdown actual
          dropdown.classList.toggle("open");
        }
      }

      // 2. Alternar la barra lateral (colapsar/expandir)
      document.addEventListener("DOMContentLoaded", function () {
        if (document.getElementById("toggle-btn")) {
          document
            .getElementById("toggle-btn")
            .addEventListener("click", function () {
              document.getElementById("sidebar").classList.toggle("collapsed");
            });
        }
      });
    </script>

    <main class="main-content">
      <h1>Gestión de Impuestos</h1>

      <section class="impuestos-container">
        <div class="header-impuestos">
          <h2>Registro de Pagos de Impuestos</h2>
          <button id="nuevo-registro">+ Nuevo Registro</button>
        </div>

        <table class="tabla-impuestos">
          <thead>
            <tr>
              <th>Periodo</th>
              <th>Tipo</th>
              <th>Monto (S/)</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody id="tabla-body"></tbody>
        </table>
      </section>

      <section class="simulador-igv">
        <div class="titulo-igv">
          <h2>Simulador de IGV</h2>
          <button id="info-btn">?</button>
        </div>
        <div class="formulario-igv">
          <label>Total Ventas (S/):</label>
          <input type="number" id="total-ventas" placeholder="Ej: 1500" />

          <label>Total Compras (S/):</label>
          <input type="number" id="total-compras" placeholder="Ej: 800" />

          <button id="calcular-btn">Calcular IGV</button>

          <div id="resultado-igv"></div>
        </div>
      </section>

      <div id="info-box">
        <div class="info-content">
          <h2>Cómo se calcula el IGV (SUNAT)</h2>
          <p>
            En Perú, el IGV (Impuesto General a las Ventas) es del
            <strong>18%</strong>.
          </p>
          <p>El cálculo se realiza así:</p>
          <pre><code>IGV = (Ventas - Compras) × 0.18</code></pre>
          <p>
            - Si <strong>Ventas &gt; Compras</strong> → IGV a pagar.<br />
            - Si <strong>Compras &gt; Ventas</strong> → Crédito fiscal (saldo a
            favor).
          </p>
          <button id="close-info">Cerrar</button>
        </div>
      </div>

      <div id="modal-registro" class="modal">
        <div class="modal-content">
          <span class="close-modal-btn">&times;</span>
          <h2>Añadir Nuevo Impuesto</h2>
          <form id="form-nuevo-impuesto">
            <label for="reg-periodo">Periodo:</label>
            <input
              type="text"
              id="reg-periodo"
              required
              placeholder="Ej: Agosto 2025"
            />

            <label for="reg-tipo">Tipo de Impuesto:</label>
            <select id="reg-tipo" required>
              <option value="">Seleccione...</option>
              <option value="IGV">IGV</option>
              <option value="Renta">Renta</option>
              <option value="Otro">Otro</option>
            </select>

            <label for="reg-monto">Monto (S/):</label>
            <input
              type="number"
              id="reg-monto"
              required
              min="0.01"
              step="0.01"
              placeholder="Ej: 540.50"
            />

            <button type="submit" id="guardar-registro">
              Guardar Registro
            </button>
          </form>
        </div>
      </div>
      <div id="modal-eliminar" class="modal">
        <div class="modal-content">
          <h2>Confirmar Eliminación</h2>
          <p>¿Está seguro de eliminar este impuesto?</p>
          <div class="modal-buttons">
            <button id="confirmar-eliminar">Eliminar</button>
            <button id="cancelar-eliminar">Cancelar</button>
          </div>
        </div>
      </div>
    </main>

    <script src="script_contador_impuestos.js"></script>
  </body>
</html>
