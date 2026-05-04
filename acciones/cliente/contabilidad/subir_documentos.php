<?php
/**
 * Vista: Subir Documentos (Cliente)
 * ¡CORRECCIÓN!: Se añadió validación de sesión para proteger la vista.
 */
require_once '../../../configuracion/config.php';
requerirAutenticacion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Documentos - Contabilidad</title>
    <link rel="stylesheet" href="./style_subir_documentos.css" />
</head>
<style>
/* ======= TU CSS AJUSTADO ======= */

.sidebar button.menu-toggle {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.submenu {
  display: none;
  flex-direction: column;
  padding-left: 20px;
}

.submenu.show {
  display: flex;
}

.sidebar .arrow {
  margin-left: auto;
  transition: transform 0.3s;
}

body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #eaf1fb;
  color: #003366;
  display: flex;
}

/* BARRA LATERAL */
.sidebar {
  background-color: #001f4d;
  width: 120px;
  height: 100vh;
  color: white;
  transition: width 0.3s;
  overflow-x: hidden;
}

</style>
<body>
    <!-- (Manteniendo exactamente tu código HTML original) -->
    <div class="main-content">
        <header>
            <div class="user-info">
                <span>Juan Pérez</span>
                <div class="avatar"></div>
            </div>
        </header>

        <div class="upload-area">
            <p><strong>Facturas y Boletas</strong></p>
            
            <div class="drag-drop-box" id="drop-zone">
                <div class="icon-cloud">☁️</div>
                <p>Arrastrar archivos aquí</p>
                <p>(o haga clic para seleccionar archivos)</p>
                <input type="file" id="file-input" multiple accept=".xml" hidden>
            </div>

            <div class="file-selection-footer">
                <input type="text" placeholder="Seleccionar archivos..." disabled class="file-path-display">
                <button class="examinar-button" id="select-files-btn">Examinar...</button>
            </div>
        </div>
    </div>

<!-- ======================================================== -->
<!-- ======================== JS ============================ -->
<!-- ======================================================== -->

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("expanded");
}

function toggleSubmenu() {
    const submenu = document.getElementById("submenu");
    const arrow = document.getElementById("arrow");

    submenu.classList.toggle("show");

    if (submenu.classList.contains("show")) {
        arrow.style.transform = "rotate(180deg)";
    } else {
        arrow.style.transform = "rotate(0deg)";
    }
}
function toggleDropdown() {
  const sidebar = document.getElementById("sidebar");
  const dropdown = document.querySelector(".dropdown");

  if (!sidebar.classList.contains("expanded")) return;

  dropdown.classList.toggle("open");
}

</script>

<script src="./script_subir_documentos.js"></script>
</body>
</html>