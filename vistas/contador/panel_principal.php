<?php
/**
 * Vista: Panel Principal - Contador
 * Reemplaza: index_contador.html
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
    <title>Panel del Contador - Taller Contable</title>
    <link rel="stylesheet" href="../../publico/estilos/principal.css">
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="toggle-btn" id="toggle-btn">☰ Menú</button>
        <ul>
            <li><a href="#"><span class="emoji">🏠</span><span class="text">Inicio</span></a></li>
            <li><a href="../../acciones/contador/ingresos/ingresos_contador.php">
                <span class="emoji">💰</span><span class="text">Ingresos</span></a></li>
            <li><a href="../../acciones/contador/egresos/egresos_contador.php">
                <span class="emoji">💸</span><span class="text">Egresos</span></a></li>
            <li><a href="../../acciones/contador/impuestos/impuestos_contador.php">
                <span class="emoji">🧮</span><span class="text">Impuestos</span></a></li>
            <li><a href="../../acciones/contador/reportes/reportes_contador.php">
                <span class="emoji">📈</span><span class="text">Reportes</span></a></li>
            <li><a href="../../controladores/autenticacion.php?accion=logout">
                <span class="emoji">🚪</span><span class="text">Cerrar Sesión</span></a></li>
        </ul>
    </nav>

    <!-- Contenido Principal -->
    <main class="main-content">
        <!-- Header -->
        <header class="top-bar">
            <div class="user-heading">
                <h1 class="mi-cuenta">Panel del Contador</h1>
                <p class="greeting-text" id="bienvenida-usuario">Bienvenido, <?php echo htmlspecialchars(obtenerCorreoUsuario()); ?></p>
                <div class="contador-info-card">
                    <p class="info-label">Cliente Seleccionado:</p>
                    <p class="info-data" id="cliente-nombre">--</p>
                </div>
            </div>
            <div class="top-info">
                <span class="top-icon">🔔</span>
                <span class="top-icon">⚙️</span>
            </div>
        </header>

        <!-- Quick Stats -->
        <section class="quick-stats-container">
            <div class="stat-card stat-ingresos">
                <span class="stat-emoji">💰</span>
                <p class="stat-title">Ingresos del Mes</p>
                <p id="stat-ingresos-value" class="stat-value">S/ 0.00</p>
            </div>

            <div class="stat-card stat-egresos">
                <span class="stat-emoji">💸</span>
                <p class="stat-title">Egresos del Mes</p>
                <p id="stat-egresos-value" class="stat-value">S/ 0.00</p>
            </div>

            <div class="stat-card stat-documentos">
                <span class="stat-emoji">📄</span>
                <p class="stat-title">Documentos Pendientes</p>
                <p class="stat-value">0</p>
            </div>
        </section>

        <!-- Menu de Acciones -->
        <div class="menu-container">
            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">💰</span>
                </div>
                <h3>Registrar Ingresos</h3>
                <p class="info">Registra comprobantes de ingresos</p>
                <button onclick="window.location.href='../../acciones/contador/ingresos/ingresos_contador.php'">Acceder</button>
            </div>

            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">💸</span>
                </div>
                <h3>Registrar Egresos</h3>
                <p class="info">Registra comprobantes de egresos</p>
                <button onclick="window.location.href='../../acciones/contador/egresos/egresos_contador.php'">Acceder</button>
            </div>

            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">🧮</span>
                </div>
                <h3>Gestionar Impuestos</h3>
                <p class="info">Administra impuestos y contribuciones</p>
                <button onclick="window.location.href='../../acciones/contador/impuestos/impuestos_contador.php'">Acceder</button>
            </div>

            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">📈</span>
                </div>
                <h3>Ver Reportes</h3>
                <p class="info">Consulta reportes financieros</p>
                <button onclick="window.location.href='../../acciones/contador/reportes/reportes_contador.php'">Acceder</button>
            </div>

            <div class="menu-card">
                <div class="img-ref">
                    <span style="font-size: 60px;">👥</span>
                </div>
                <h3>Seleccionar Cliente</h3>
                <p class="info">Cambia el cliente seleccionado</p>
                <button id="btnSeleccionarCliente">Seleccionar</button>
            </div>
        </div>
    </main>

    <!-- Modal de Selección de Clientes -->
    <div id="modalClientes" role="dialog" aria-hidden="true">
        <div class="modal-clientes" role="document" aria-modal="true">
            <div class="modal-header">
                <div class="titulo">Seleccionar Cliente</div>
                <button id="cerrarModalClientes" aria-label="Cerrar">✖</button>
            </div>
            <input id="buscadorClientes" type="text" placeholder="Buscar cliente..." autocomplete="off">
            <div id="listaClientes" class="lista-clientes"></div>
        </div>
    </div>

    <script src="../../publico/scripts/principal.js"></script>
</body>
</html>
