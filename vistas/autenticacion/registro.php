<?php
// registro.php
// Vista de registro con TailwindCSS y validación externa.
declare(strict_types=1);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - ContaPlus</title>
    <meta name="description" content="Formulario de registro para crear una nueva cuenta en ContaPlus." />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: "#2F5BEA",
                            hover: "#2448bf",
                            light: "#EEF4FF",
                        },
                        texto: {
                            primario: "#0F172A",
                            secundario: "#64748B",
                        },
                        border: {
                            sutil: "#E2E8F0",
                        },
                    },
                    boxShadow: {
                        card: "0 20px 60px rgba(15, 23, 42, 0.08)",
                    },
                },
            },
        };
    </script>
    <style>
        /* Ajuste moderno para selects y radios sin estilos nativos obsoletos */
        select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-texto-primario antialiased flex flex-col">

    <!-- Header -->
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur-sm sticky top-0 z-50">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="#" class="text-2xl font-bold tracking-tight text-brand-primary">Registro</a>
            <div class="flex items-center gap-6">
                <a href="inicio_sesion.php" class="text-sm font-medium text-texto-secundario transition hover:text-brand-primary">Login</a>
                <button type="button" class="grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200" aria-label="Ayuda">?</button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow mx-auto flex w-full max-w-7xl justify-center px-4 py-12 sm:px-6 lg:px-8">
        <section class="w-full max-w-5xl rounded-3xl border border-slate-200 bg-white p-6 shadow-card sm:p-10 lg:p-14">
            
            <!-- Título -->
            <div class="mb-10">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Crear nueva cuenta</h1>
                <p class="mt-2 text-base text-texto-secundario sm:text-lg">Complete la información para comenzar con su gestión contable.</p>
            </div>

            <!-- Formulario -->
            <form id="registroForm" action="../../controladores/autenticacion.php?accion=registro" method="POST" class="space-y-10" novalidate>
                
                <!-- Sección 1: Datos Personales -->
                <section aria-labelledby="datos-personales-title">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-light text-brand-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10 2a5 5 0 00-5 5v1a5 5 0 1010 0V7a5 5 0 00-5-5z" />
                                <path d="M4 14a6 6 0 1112 0v1H4v-1z" />
                            </svg>
                        </span>
                        <h2 id="datos-personales-title" class="text-2xl font-bold text-slate-900">Datos Personales</h2>
                    </div>
                    <div class="mt-5 border-t border-slate-200 pt-8">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="nombre_contacto" class="mb-2 block text-sm font-semibold text-slate-700">Nombre Completo</label>
                                <input id="nombre_contacto" name="nombre_contacto" type="text" autocomplete="name" placeholder="Ej. Juan Pérez" class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required />
                            </div>
                            <div>
                                <label for="nombre_negocio" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del Negocio</label>
                                <input id="nombre_negocio" name="nombre_negocio" type="text" autocomplete="organization" placeholder="Nombre comercial" class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required />
                            </div>
                            <div>
                                <label for="numero_contacto" class="mb-2 block text-sm font-semibold text-slate-700">Número de Teléfono</label>
                                <input id="numero_contacto" name="numero_contacto" type="tel" inputmode="tel" autocomplete="tel" placeholder="+52 ..." class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required />
                            </div>
                            <div>
                                <label for="tipo_usuario" class="mb-2 block text-sm font-semibold text-slate-700">Tipo de Usuario</label>
                                <select id="tipo_usuario" name="tipo_usuario" class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Cliente">Cliente</option>
                                    <option value="Contador">Contador</option>
                                </select>
                            </div>
                            <div>
                                <label for="correo" class="mb-2 block text-sm font-semibold text-slate-700">Correo Electrónico</label>
                                <input id="correo" name="correo" type="email" autocomplete="email" placeholder="usuario@ejemplo.com" class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required />
                            </div>
                            <div>
                                <label for="clave" class="mb-2 block text-sm font-semibold text-slate-700">Contraseña</label>
                                <input id="clave" name="clave" type="password" autocomplete="new-password" placeholder="••••••••" class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sección 2: Datos Tributarios -->
                <section aria-labelledby="datos-tributarios-title">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-light text-brand-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L13 1.586A2 2 0 0011.586 1H4z" />
                            </svg>
                        </span>
                        <h2 id="datos-tributarios-title" class="text-2xl font-bold text-slate-900">Datos Tributarios</h2>
                    </div>
                    <div class="mt-5 border-t border-slate-200 pt-8">
                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                            <div>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Tipo de Cliente</span>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipo_cliente" value="NATURAL" class="peer sr-only" required />
                                        <span class="flex items-center justify-center rounded-xl border border-border-sutil bg-white px-4 py-4 text-sm font-medium text-slate-700 transition peer-checked:border-brand-primary peer-checked:bg-brand-light peer-checked:text-brand-primary hover:border-brand-primary">Persona Física</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipo_cliente" value="JURIDICO" class="peer sr-only" required />
                                        <span class="flex items-center justify-center rounded-xl border border-border-sutil bg-white px-4 py-4 text-sm font-medium text-slate-700 transition peer-checked:border-brand-primary peer-checked:bg-brand-light peer-checked:text-brand-primary hover:border-brand-primary">Persona Moral</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label for="regimen" class="mb-2 block text-sm font-semibold text-slate-700">Régimen Fiscal</label>
                                <select id="regimen" name="regimen" class="w-full rounded-xl border border-border-sutil bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-brand-primary focus:bg-white focus:ring-4 focus:ring-brand-primary/10" required>
                                    <option value="">Seleccione su régimen</option>
                                    <option value="NRUS">Nuevo Régimen Único Simplificado</option>
                                    <option value="RER">Régimen Especial de Impuesto a la Renta</option>
                                    <option value="RMT">Régimen MYPE Tributario</option>
                                    <option value="RG">Régimen General</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Alerta de Validación -->
                <div id="formAlert" class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 transition-all" role="alert" aria-live="polite"></div>

                <!-- Botón de Envío -->
                <div class="pt-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-3 rounded-xl bg-brand-primary px-6 py-4 text-base font-semibold text-white shadow-lg shadow-brand-primary/20 transition-transform hover:-translate-y-0.5 hover:bg-brand-hover focus:outline-none focus:ring-4 focus:ring-brand-primary/20">
                        Registrar Cuenta
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h10.586L11.293 5.707a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 11-1.414-1.414L14.586 11H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <p class="mt-4 text-center text-sm text-texto-secundario">
                        Al registrarte, aceptas nuestros
                        <a href="#" class="font-semibold text-brand-primary hover:underline">Términos de Servicio</a> y
                        <a href="#" class="font-semibold text-brand-primary hover:underline">Política de Privacidad</a>.
                    </p>
                </div>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-slate-50 py-10 mt-auto">
        <div class="mx-auto max-w-7xl px-6 text-center">
            <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-texto-secundario">
                <a href="#" class="transition hover:text-brand-primary">Privacy Policy</a>
                <a href="#" class="transition hover:text-brand-primary">Terms of Service</a>
                <a href="#" class="transition hover:text-brand-primary">Contact Support</a>
            </div>
            <p class="mt-8 text-sm text-texto-secundario">© 2024 Registration Portal. All rights reserved.</p>
        </div>
    </footer>

    <!-- Script de Validación (Ruta corregida con barras forward) -->
    <script src="publico/scripts/validar_registro.js" defer></script>
</body>
</html>