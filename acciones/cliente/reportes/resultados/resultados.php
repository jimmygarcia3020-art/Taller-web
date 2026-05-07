<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Estado de Resultados</title>
    <style>
      body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #eaf1fb;
        color: #003366;
        display: flex;
      }

      /* ===== BARRA LATERAL ===== */
      .sidebar {
        background-color: #001f4d;
        width: 120px;
        height: 100vh;
        color: white;
        transition: width 0.3s;
        overflow: hidden;
        flex-shrink: 0;
      }

      .sidebar.expanded {
        width: 230px;
      }

      .sidebar button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: none;
        border: none;
        color: white;
        padding: 22px 0;
        font-size: 16px;
        cursor: pointer;
      }

      .sidebar button:hover {
        background-color: #002a73;
      }

      /* ===== SUBMENÚ ===== */
      .dropdown {
        width: 100%;
      }

      .dropdown-content {
        display: none;
        flex-direction: column;
        background-color: #003366;
      }

      .dropdown-content button {
        font-size: 14px;
        text-align: left;
        padding: 12px 25px;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
      }

      .dropdown-content button:hover {
        background-color: #004a99;
      }

      .dropdown.open .dropdown-content {
        display: flex;
      }

      /* ===== CONTENIDO PRINCIPAL ===== */
      .main-content {
        flex: 1;
        padding: 20px;
      }

      .header {
        background-color: #001f4d;
        color: white;
        padding: 15px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        border-radius: 5px;
      }

      /* ===== TABLA ===== */
      .resultados {
        width: 90%;
        margin: 30px auto;
        background: white;
        border-radius: 8px;
        border: 1px solid #004a99;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
      }

      th,
      td {
        padding: 10px;
        border-bottom: 1px solid #dce6f7;
        text-align: left;
      }

      th {
        background-color: #cfe0ff;
        color: #003366;
      }

      tr.total {
        background-color: #f1f6ff;
        font-weight: bold;
      }

      tr.final {
        background-color: #e3edff;
        font-weight: bold;
        color: #004a99;
      }

      /* ===== BOTONES ===== */
      .btn-container {
        text-align: right;
        margin-bottom: 10px;
      }

      .btn {
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        margin-left: 5px;
        cursor: pointer;
        font-weight: bold;
      }

      .btn-ejecutar {
        background-color: #d81b60;
        color: white;
      }

      .btn-ejecutar:hover {
        background-color: #a01247;
      }

      .btn-exportar {
        background-color: #004a99;
        color: white;
      }

      .btn-exportar:hover {
        background-color: #002a73;
      }
      /* SIDEBAR GENERAL */
      .sidebar {
        width: 220px;
        transition: width 0.3s;
        overflow: hidden;
      }

      .sidebar.expanded {
        width: 220px; /* ancho normal */
      }

      .sidebar:not(.expanded) {
        width: 70px; /* ANCHO REDUCIDO */
      }

      /* Texto de los botones */
      .sidebar button span {
        opacity: 1;
        transition: opacity 0.2s;
        white-space: nowrap;
      }

      /* OCULTAR TEXTO CUANDO ESTÁ CONTRAÍDO */
      .sidebar:not(.expanded) button span {
        opacity: 0;
        display: none;
      }

      /* ÍCONOS grandes cuando el menú está contraído */
      .sidebar:not(.expanded) button {
        font-size: 26px;
        justify-content: center;
      }

      /* ÍCONOS tamaño normal cuando está expandido */
      .sidebar.expanded button {
        font-size: 18px;
        justify-content: flex-start;
      }

      /* Flecha de reportes oculta cuando está contraído */
      .sidebar:not(.expanded) .arrow {
        display: none;
      }

      /* Evitar que el menú de reportes se despliegue cuando está contraído */
      .sidebar:not(.expanded) .dropdown-btn {
        pointer-events: none;
        opacity: 0.6;
      }

      /* Submenú oculto cuando está contraído */
      .sidebar:not(.expanded) .dropdown-content {
        display: none !important;
      }

      /* Submenú visible al abrir */
      .dropdown.open .dropdown-content {
        display: flex;
        flex-direction: column;
      }
    </style>
  </head>
  <body>
    <!-- ===== BARRA LATERAL ===== -->
    <div class="sidebar" id="sidebar">
      <button onclick="toggleSidebar()">☰</button>

       <!-- Inicio -->
       <button
         onclick="
           window.location.href = '../../../../vistas/cliente/panel_principal.php'
         "
       >
         🏠<span>Inicio</span>
       </button>

       <!-- Chat -->
       <button onclick="window.location.href = '../../chats/chats.php'">
         💬<span>Chat</span>
       </button>

       <!-- Subir Documentos -->
       <button
         onclick="
           window.location.href = '../../contabilidad/subir_documentos.php'
         "
       >
         ⏫<span>Subir Documentos</span>
       </button>

      <!-- Submenú de Reportes -->
      <div class="dropdown">
        <button class="dropdown-btn" onclick="toggleDropdown()">
          📁<span>Reportes</span>
        </button>

         <div class="dropdown-content" id="reportesDropdown">
           <button onclick="window.location.href = '#'">
             📊 Reportes Contables
           </button>

           <button onclick="window.location.href = '../reportes/reportes.php'">
             📈 Estado de Resultado
           </button>
         </div>
      </div>

      <form
        action="../../../../controladores/autenticacion.php?accion=logout"
        method="post"
        style="margin: 0; padding: 0"
      >
        <button type="submit">📤<span>Cerrar Sesión</span></button>
      </form>
    </div>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <div class="main-content">
      <div class="header">💼 Estado de Resultados</div>

      <div class="resultados">
        <div class="btn-container">
          <button class="btn btn-ejecutar">Ejecutar</button>
          <button class="btn btn-exportar">📄</button>
          <button class="btn btn-exportar">📘</button>
        </div>

        <table>
          <tr>
            <th>Cuenta</th>
            <th>Glosa</th>
            <th>Subtotal S/</th>
            <th>Total S/</th>
          </tr>

          <tr>
            <td></td>
            <td><b>INGRESOS</b></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>70</td>
            <td>VENTAS</td>
            <td></td>
            <td>9,127.34</td>
          </tr>
          <tr class="total">
            <td></td>
            <td>Total INGRESOS S/:</td>
            <td></td>
            <td>9,127.34</td>
          </tr>

          <tr>
            <td></td>
            <td><b>GASTOS</b></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>94</td>
            <td>GASTOS ADMINISTRATIVOS</td>
            <td></td>
            <td>5,694.10</td>
          </tr>
          <tr>
            <td>95</td>
            <td>GASTOS DE VENTAS</td>
            <td></td>
            <td>5,694.10</td>
          </tr>
          <tr>
            <td>97</td>
            <td>GASTOS FINANCIEROS</td>
            <td></td>
            <td>6.70</td>
          </tr>
          <tr class="total">
            <td></td>
            <td>Total GASTOS S/:</td>
            <td></td>
            <td>11,394.89</td>
          </tr>

          <tr class="final">
            <td></td>
            <td>Utilidad Neta S/:</td>
            <td></td>
            <td>2,267.55</td>
          </tr>
        </table>
      </div>
    </div>

    <script>
      function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("expanded");
      }

      function toggleDropdown() {
        const sidebar = document.getElementById("sidebar");
        const dropdown = document.querySelector(".dropdown");

        // Evitar abrir si el sidebar está contraído
        if (!sidebar.classList.contains("expanded")) return;

        dropdown.classList.toggle("open");
      }
    </script>
  </body>
</html>
