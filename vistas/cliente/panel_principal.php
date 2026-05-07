<?php
/**
 * Vista: Panel Principal - Cliente
 */

require_once '../../configuracion/config.php';

requerirAutenticacion();
prevenirCache();

// Evitamos el error fatal obteniendo el correo de la sesión de forma segura
$correoActivo = obtenerCorreoUsuario();
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
            <li><a href="panel_principal.php"><span class="emoji">📊</span><span class="text">Dashboard</span></a></li>
            <li class="dropdown" id="contabilidadDropdown">
                <button class="dropdown-btn" onclick="this.parentElement.classList.toggle('open')">
                    <span class="emoji">📈</span><span class="text">Contabilidad</span>
                </button>
                <div class="dropdown-content">
                    <button onclick="window.location.href='../../acciones/cliente/contabilidad/subir_documentos.php'">Subir Documentos</button>
                    <button onclick="window.location.href='../../acciones/cliente/reportes/reportes/reportes.php'">Ver Informes</button>
                </div>
            </li>
            <li class="dropdown" id="reportesDropdown">
                <button class="dropdown-btn" onclick="this.parentElement.classList.toggle('open')">
                    <span class="emoji">📁</span><span class="text">Reportes</span>
                </button>
                <div class="dropdown-content">
                    <button onclick="window.location.href='../../acciones/cliente/reportes/resultados/resultados.php'">Resultados Generales</button>
                </div>
            </li>
            <li class="dropdown" id="comunicacionesDropdown">
                <button class="dropdown-btn" onclick="this.parentElement.classList.toggle('open')">
                    <span class="emoji">💬</span><span class="text">Comunicaciones</span>
                </button>
                <div class="dropdown-content">
                    <button onclick="window.location.href='../../acciones/cliente/chats/asesoramiento.php'">Asesoramiento</button>
                    <button onclick="window.location.href='../../acciones/cliente/chats/chats.php'">Chat con Asesor</button>
                    <button onclick="window.location.href='../../acciones/cliente/chats/libros.php'">Libros Electrónicos</button>
                </div>
            </li>
            <li>
                <a href="../../controladores/autenticacion.php?accion=logout" style="color: #ff4757;">
                    <span class="emoji">🚪</span><span class="text">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header>
            <div class="header-title">
                <h2>Bienvenido de nuevo 👋</h2>
                <p>Resumen financiero actualizado</p>
            </div>
            <div class="user-info">
                <span><?php echo htmlspecialchars($correoActivo); ?></span>
                <div class="avatar">
                    <img src="../../publico/imagenes/user.jpg" alt="Usuario" style="width: 40px; height: 40px; border-radius: 50%;">
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card stat-ingresos">
                <span class="stat-emoji">💰</span>
                <p class="stat-title">Ingresos Mensuales</p>
                <p class="stat-value">S/ 0.00</p>
            </div>
            <div class="stat-card stat-egresos">
                <span class="stat-emoji">📉</span>
                <p class="stat-title">Gastos del Mes</p>
                <p class="stat-value">S/ 0.00</p>
            </div>
            <div class="stat-card stat-ingresos">
                <span class="stat-emoji">🧾</span>
                <p class="stat-title">Impuestos Estimados</p>
                <p class="stat-value">S/ 0.00</p>
            </div>
            <div class="stat-card stat-ingresos">
                <span class="stat-emoji">🎯</span>
                <p class="stat-title">Estado</p>
                <p class="stat-value">Al día</p>
            </div>
        </div>

        <!-- Menu de Acciones Rápidas -->
        <div class="menu-container" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 30px;">
            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 250px;">
                <div class="img-ref">
                    <span style="font-size: 60px;">📤</span>
                </div>
                <h3>Subir Documentos</h3>
                <p class="info" style="color: #666; font-size: 14px;">Sube tus comprobantes y facturas</p>
                <button style="background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;" onclick="window.location.href='../../acciones/cliente/contabilidad/subir_documentos.php'">Acceder</button>
            </div>

            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 250px;">
                <div class="img-ref">
                    <span style="font-size: 60px;">📊</span>
                </div>
                <h3>Ver Reportes</h3>
                <p class="info" style="color: #666; font-size: 14px;">Consulta tus reportes contables</p>
                <button style="background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;" onclick="window.location.href='../../acciones/cliente/reportes/resultados/resultados.php'">Acceder</button>
            </div>

            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 250px;">
                <div class="img-ref">
                    <span style="font-size: 60px;">💬</span>
                </div>
                <h3>Chats</h3>
                <p class="info" style="color: #666; font-size: 14px;">Comunícate con tu asesor</p>
                <button style="background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;" onclick="window.location.href='../../acciones/cliente/chats/chats.php'">Acceder</button>
            </div>
        </div>
    </div>

    <script src="../../publico/scripts/principal.js"></script>
</body>
</html>