<?php
/**
 * Vista: Panel Principal - Contador
 */

require_once '../../configuracion/config.php';

requerirAutenticacion();
prevenirCache();

// Obtenemos el correo del contador logueado de forma segura
$correoActivo = obtenerCorreoUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Contador - Taller Contable</title>
    <link rel="stylesheet" href="../../publico/estilos/principal.css">
    <style>
        /* Estilos básicos para el Modal en caso de que falten en tu CSS principal */
        #modalClientes { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-clientes { background: white; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .modal-header button { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #ff4757; }
        #buscadorClientes { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .lista-clientes { max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="toggle-btn" id="toggle-btn">☰ Menú</button>
        <ul>
            <li><a href="panel_principal.php"><span class="emoji">🏠</span><span class="text">Inicio</span></a></li>
            <li><a href="../../acciones/contador/ingresos/ingresos_contador.php">
                <span class="emoji">💰</span><span class="text">Ingresos</span></a></li>
            <li><a href="../../acciones/contador/egresos/egresos_contador.php">
                <span class="emoji">💸</span><span class="text">Egresos</span></a></li>
            <li><a href="../../acciones/contador/impuestos/impuestos_contador.php">
                <span class="emoji">🧮</span><span class="text">Impuestos</span></a></li>
            <li><a href="../../acciones/contador/reportes/reportes_contador.php">
                <span class="emoji">📈</span><span class="text">Reportes</span></a></li>
            <li style="margin-top: 20px;">
                <a href="../../controladores/autenticacion.php?accion=logout" style="color: #ff4757;">
                    <span class="emoji">🚪</span><span class="text">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header>
            <div class="header-title">
                <h2>Panel de Contador 📊</h2>
                <p>Gestión contable y financiera</p>
            </div>
            <div class="user-info">
                <span><?php echo htmlspecialchars($correoActivo); ?></span>
                <div class="avatar">
                    <img src="../../publico/imagenes/user.jpg" alt="Usuario" style="width: 40px; height: 40px; border-radius: 50%;">
                </div>
            </div>
        </header>

        <!-- Indicador de cliente -->
        <div class="stats-grid">
            <div class="stat-card" style="width: 100%; max-width: 350px;">
                <span class="stat-emoji">👥</span>
                <p class="stat-title">Cliente Seleccionado</p>
                <p class="stat-value" id="clienteActivoNombre" style="font-size: 1.2rem; color: #004aad;">Ninguno</p>
            </div>
        </div>

        <!-- Menu de Acciones -->
        <div class="menu-container" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 30px;">
            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 200px;">
                <div class="img-ref">
                    <span style="font-size: 50px;">💰</span>
                </div>
                <h3>Registrar Ingresos</h3>
                <p class="info" style="color: #666; font-size: 14px;">Añade nuevas ventas</p>
                <button style="background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;" onclick="window.location.href='../../acciones/contador/ingresos/ingresos_contador.php'">Acceder</button>
            </div>

            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 200px;">
                <div class="img-ref">
                    <span style="font-size: 50px;">💸</span>
                </div>
                <h3>Registrar Egresos</h3>
                <p class="info" style="color: #666; font-size: 14px;">Añade nuevos gastos</p>
                <button style="background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;" onclick="window.location.href='../../acciones/contador/egresos/egresos_contador.php'">Acceder</button>
            </div>

            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 200px;">
                <div class="img-ref">
                    <span style="font-size: 50px;">📈</span>
                </div>
                <h3>Ver Reportes</h3>
                <p class="info" style="color: #666; font-size: 14px;">Consulta reportes financieros</p>
                <button style="background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;" onclick="window.location.href='../../acciones/contador/reportes/reportes_contador.php'">Acceder</button>
            </div>

            <div class="menu-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; flex: 1; min-width: 200px;">
                <div class="img-ref">
                    <span style="font-size: 50px;">👥</span>
                </div>
                <h3>Seleccionar Cliente</h3>
                <p class="info" style="color: #666; font-size: 14px;">Cambia el cliente a auditar</p>
                <button id="btnSeleccionarCliente" style="background: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;">Seleccionar</button>
            </div>
        </div>
    </main>

    <!-- Modal de Selección de Clientes -->
    <div id="modalClientes" role="dialog" aria-hidden="true">
        <div class="modal-clientes" role="document" aria-modal="true">
            <div class="modal-header">
                <div class="titulo" style="font-weight:bold; font-size:1.2rem;">Seleccionar Cliente</div>
                <button id="cerrarModalClientes" aria-label="Cerrar">✖</button>
            </div>
            <input id="buscadorClientes" type="text" placeholder="Buscar por nombre o RUC..." autocomplete="off">
            <div id="listaClientes" class="lista-clientes">
                <!-- Se llena mediante principal.js -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../publico/scripts/principal.js"></script>
    <script>
        // Lógica de UI para abrir/cerrar el modal si no está cubierto al 100% en principal.js
        document.addEventListener("DOMContentLoaded", () => {
            const btnAbrir = document.getElementById("btnSeleccionarCliente");
            const modal = document.getElementById("modalClientes");
            const btnCerrar = document.getElementById("cerrarModalClientes");
            
            if(btnAbrir && modal && btnCerrar) {
                btnAbrir.addEventListener("click", () => modal.style.display = "flex");
                btnCerrar.addEventListener("click", () => modal.style.display = "none");
                window.addEventListener("click", (e) => {
                    if(e.target === modal) modal.style.display = "none";
                });
            }

            // Mostrar el cliente activo actual si existe en localStorage
            const nombreActivo = localStorage.getItem("cliente_activo_nombre");
            if (nombreActivo) {
                const tagNombre = document.getElementById("clienteActivoNombre");
                if(tagNombre) tagNombre.textContent = nombreActivo;
            }
        });
    </script>
</body>
</html>