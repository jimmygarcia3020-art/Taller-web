<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - ContaPlus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../publico/estilos/inicio_sesion.css">    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary:  '#2E5EAA',
                            hover:    '#234d8f',
                            light:    '#EBF2FF',
                            dark:     '#1a3d78',
                        },
                        surface: {
                            base: '#F8FAFC',
                            card: '#FFFFFF',
                        },
                        texto: {
                            primario:   '#1F2937',
                            secundario: '#727785',
                        },
                        border: { sutil: '#E5E7EB' },
                    },
                    fontFamily: {
                        sora: ['Sora', 'sans-serif'],
                        dm:   ['DM Sans', 'sans-serif'],
                    },
                    keyframes: {
                        fadeUp: {
                            '0%':   { opacity: '0', transform: 'translateY(16px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        shake: {
                            '0%,100%': { transform: 'translateX(0)' },
                            '20%,60%': { transform: 'translateX(-6px)' },
                            '40%,80%': { transform: 'translateX(6px)' },
                        },
                    },
                    animation: {
                        'fade-up':   'fadeUp 0.55s ease both',
                        'fade-up-1': 'fadeUp 0.55s 0.08s ease both',
                        'fade-up-2': 'fadeUp 0.55s 0.16s ease both',
                        'fade-up-3': 'fadeUp 0.55s 0.24s ease both',
                        'shake':     'shake 0.4s ease',
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="font-dm text-texto-primario min-h-screen flex flex-col">

    <!-- ===== HEADER MARCA ===== -->
    <header class="pt-10 pb-6 text-center animate-fade-up">
        <!-- Ícono de marca -->
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-primary shadow-lg shadow-brand-primary/30 mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01
                         M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="font-sora text-2xl font-extrabold text-texto-primario tracking-tight">ContaPlus</h1>
        <p class="text-texto-secundario text-sm mt-1">Gestión financiera simplificada</p>
    </header>

    <!-- ===== CARD DE LOGIN ===== -->
    <main class="flex-1 flex items-start justify-center px-4 pb-10">
        <div class="w-full max-w-md">

            <!-- Banner de bloqueo (oculto por defecto) -->
            <div id="lockBanner" class="hidden mb-4 bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                <p class="text-red-600 font-semibold text-sm" id="lockMessage">Cuenta bloqueada temporalmente</p>
                <div class="mt-2 h-1.5 bg-red-100 rounded-full overflow-hidden">
                    <div id="lockBar" class="h-full bg-red-400 rounded-full w-full"></div>
                </div>
                <p class="text-red-400 text-xs mt-1.5">Espera <span id="lockCountdown">30</span>s para volver a intentar</p>
            </div>

            <!-- Card principal -->
            <div class="bg-surface-card rounded-2xl shadow-xl shadow-gray-200/80 border border-border-sutil p-8 animate-fade-up-1">

                <h2 class="font-sora text-xl font-bold text-texto-primario mb-6">Bienvenido de nuevo</h2>

                <form id="loginForm"
                      action="../../controladores/autenticacion.php?accion=login"
                      method="POST"
                      novalidate>

                    <!-- Campo Email -->
                    <div class="mb-4 animate-fade-up-2">
                        <label for="correo" class="block text-sm font-semibold text-texto-primario mb-1.5">
                            Email
                        </label>
                        <div class="relative">
                            <!-- Ícono izquierda -->
                            <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-texto-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input
                                id="correo"
                                name="correo"
                                type="email"
                                placeholder="ejemplo@correo.com"
                                autocomplete="email"
                                class="input-field w-full pl-10 pr-4 py-3 border-2 border-border-sutil rounded-xl text-sm bg-surface-base transition-all duration-200"
                            >
                        </div>
                        <span id="errorCorreo" class="field-error"></span>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="mb-2 animate-fade-up-3">
                        <label for="clave" class="block text-sm font-semibold text-texto-primario mb-1.5">
                            Contraseña
                        </label>
                        <div class="relative">
                            <!-- Ícono izquierda -->
                            <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-texto-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input
                                id="clave"
                                name="clave"
                                type="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                class="input-field w-full pl-10 pr-12 py-3 border-2 border-border-sutil rounded-xl text-sm bg-surface-base transition-all duration-200"
                            >
                            <!-- Toggle mostrar/ocultar contraseña -->
                            <button
                                type="button"
                                id="togglePassword"
                                aria-label="Mostrar contraseña"
                                class="absolute inset-y-0 right-3.5 flex items-center text-texto-secundario hover:text-brand-primary transition-colors">
                                <!-- Ojo abierto (visible cuando password está oculto) -->
                                <svg id="iconEyeOff" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                                             a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                                             M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29
                                             M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7
                                             a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                                <!-- Ojo cerrado (visible cuando password está visible) -->
                                <svg id="iconEyeOn" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <span id="errorClave" class="field-error"></span>
                    </div>

                    <!-- Olvidaste contraseña -->
                    <div class="text-right mb-6">
                        <a href="#" class="text-sm font-semibold text-brand-primary hover:text-brand-hover transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Botón submit -->
                    <button
                        id="btnSubmit"
                        type="submit"
                        class="w-full bg-brand-primary hover:bg-brand-hover text-white font-bold py-3.5 px-6 rounded-xl
                               transition-all duration-200 hover:-translate-y-0.5 shadow-md shadow-brand-primary/25
                               flex items-center justify-center gap-2 font-sora text-sm">
                        Iniciar Sesión
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <!-- Separador -->
                    <div class="flex items-center gap-3 my-5">
                        <div class="flex-1 h-px bg-border-sutil"></div>
                        <span class="text-texto-secundario text-xs">O continúa con</span>
                        <div class="flex-1 h-px bg-border-sutil"></div>
                    </div>

                    <!-- Registro -->
                    <p class="text-center text-sm text-texto-secundario">
                        ¿No tienes cuenta?
                        <a href="registro.php" class="font-bold text-brand-primary hover:text-brand-hover transition-colors ml-1">
                            Regístrate
                        </a>
                    </p>

                </form>
            </div>

            <!-- Nota de accesibilidad -->
            <p class="text-center text-xs text-texto-secundario mt-5 max-w-xs mx-auto leading-relaxed">
                Diseñado para ser accesible. Si necesitas ayuda con el inicio de sesión, contacta a soporte.
            </p>
        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="py-6 border-t border-border-sutil bg-white/70">
        <div class="max-w-screen-xl mx-auto px-6 flex flex-wrap justify-between items-center gap-3">
            <span class="font-sora font-bold text-texto-primario text-sm">ContaPlus</span>
            <div class="flex gap-5">
                <a href="#" class="text-texto-secundario hover:text-texto-primario text-xs transition-colors">Privacidad</a>
                <a href="#" class="text-texto-secundario hover:text-texto-primario text-xs transition-colors">Términos</a>
                <a href="#" class="text-texto-secundario hover:text-texto-primario text-xs transition-colors">Soporte</a>
            </div>
            <span class="text-texto-secundario text-xs">© 2025 ContaPlus. Todos los derechos reservados.</span>
        </div>
    </footer>

    <!-- JS externo: toda la lógica de validación e interacción -->
    <script src="../../publico/scripts/validar_inicio.js"></script>
</body>
</html>