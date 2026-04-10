<?php
/**
 * Vista: Panel Principal - Cliente
 * Reemplaza: index_cliente.html
 */

require_once '../../configuracion/config.php';

requerirAutenticacion();
prevenirCache();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Cliente - Taller Contable</title>
    <link rel="stylesheet" href="../../publico/estilos/principal.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" id="toggle-btn">☰ Menú</button>
        <ul>
            <li><a href="#"><span class="emoji">📊</span><span class="text">Dashboard</span></a></li>
            <li class="dropdown" id="contabilidadDropdown">
                <button class="dropdown-btn" onclick="this.parentElement.classList.toggle('open')">
                    <span class="emoji">📈</span><span class="text">Contabilidad</span>
                </button>
                <div class="dropdown-content">
                    <button onclick="alert('Ir a subir documentos')">Subir Documentos</button>
                    <button onclick="alert('Ver informes')">Ver Informes</button>
                </div>
            </li>
            <li class="dropdown" id="reportesDropdown">
                <button class="dropdown-btn" onclick="this.parentElement.classList.toggle('open')">
                    <span class="emoji">📋</span><span class="text">Reportes</span>
                </button>
                <div class="dropdown-content">
                    <button onclick="alert('Ver reportes')">Ver Reportes</button>
                    <button onclick="alert('Ver resultados')">Ver Resultados</button>
                </div>
            </li>
            <li class="dropdown" id="chatsDropdown">
                <button class="dropdown-btn" onclick="this.parentElement.classList.toggle('open')">
                    <span class="emoji">💬</span><span class="text">Chats</span>
                </button>
                <div class="dropdown-content">
                    <button onclick="alert('Asesoramiento')">Asesoramiento</button>
                    <button onclick="alert('Consultas')">Consultas</button>
                </div>
            </li>
            <li>
                <a href="../../controladores/autenticacion.php?accion=logout">
                    <span class="emoji">🚪</span><span class="text">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="user-heading">
                <h1 class="mi-cuenta">Panel del Cliente</h1>
                <p class="greeting-text" id="bienvenida-usuario">Bienvenido, <?php echo htmlspecialchars(obtenerCorreoUsuario()); ?></p>
            </div>
            <div class="top-info">
                <span class="top-icon">🔔</span>
                <span class="top-icon">⚙️</span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats-container">
            <div class="stat-card stat-documentos">
                <span class="stat-emoji">📄</span>
                <p class="stat-title">Documentos</p>
                <p class="stat-value">0</p>
            </div>
            <div class="stat-card stat-reportes">
                <span class="stat-emoji">📊</span>
                <p class="stat-title">Reportes</p>
                <p class="stat-value">0</p>
            </div>
            <div class="stat-card stat-ingresos">
                <span class="stat-emoji">🎯</span>
                <p class="stat-title">Estado</p>
                <p class="stat-value">Al día</p>
            </div>
        </div>

        <!-- Menu de Acciones -->
        <div class="menu-container">
            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">📤</span>
                </div>
                <h3>Subir Documentos</h3>
                <p class="info">Sube tus comprobantes y facturas</p>
                <button onclick="alert('Ir a subir documentos')">Acceder</button>
            </div>

            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">📊</span>
                </div>
                <h3>Ver Reportes</h3>
                <p class="info">Consulta tus reportes contables</p>
                <button onclick="alert('Ir a reportes')">Acceder</button>
            </div>

            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">💬</span>
                </div>
                <h3>Chats</h3>
                <p class="info">Comunícate con tu asesor</p>
                <button onclick="alert('Ir a chats')">Acceder</button>
            </div>
        </div>
    </div>

    <script src="../../publico/scripts/principal.js"></script>
</body>
</html>
