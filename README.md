# 📊 Sistema de Gestión Contable - Taller Web

Sistema web completo para gestión contable y fiscal con roles de clientes y contadores.

## 📁 Estructura del Proyecto

```
Taller-web/
├── 📂 publico/                    # Archivos públicos (CSS, JS, imágenes)
│   ├── estilos/                   # CSS consolidado
│   │   └── principal.css          # Estilos principales
│   ├── scripts/                   # JavaScript consolidado
│   │   ├── principal.js
│   │   ├── validar_inicio.js
│   │   └── validar_registro.js
│   └── imagenes/                  # Imágenes del proyecto
│
├── 📂 vistas/                     # Archivos de interfaz HTML/PHP
│   ├── autenticacion/             # Formularios de login y registro
│   ├── cliente/                   # Panel del cliente
│   ├── contador/                  # Panel del contador
│   └── estatica/                  # Página estática/landing
│
├── 📂 controladores/              # Lógica de negocio
│   └── autenticacion.php          # Manejo de login, registro, logout
│
├── 📂 api/                        # Endpoints API REST
│   ├── clientes.php
│   ├── listar_usuarios.php
│   └── guardar_cliente.php
│
├── 📂 modelos/                    # Clases y lógica de datos
│   └── base_datos.php             # Singleton para conexión BD
│
├── 📂 utilidades/                 # Funciones auxiliares
│   ├── sesiones.php               # Manejo de sesiones
│   └── validador.php              # Validaciones de datos
│
├── 📂 configuracion/              # Configuración centralizada
│   └── config.php                 # Constantes del proyecto
│
├── 📂 acciones/                   # Funcionalidades específicas (módulos)
│   ├── cliente/
│   │   ├── chats/
│   │   ├── contabilidad/
│   │   └── reportes/
│   └── contador/
│       ├── ingresos/
│       ├── egresos/
│       ├── impuestos/
│       └── reportes/
│
├── .env.example                   # Plantilla de variables de entorno
└── README.md                      # Este archivo
```

## 🚀 Configuración Inicial

### 1. Requisitos

- PHP 7.4+
- MySQL 5.7+
- Servidor web (Apache, Nginx, etc.)

### 2. Instalación de Base de Datos

```sql
-- Script SQL básico
CREATE DATABASE proyecto_taller;

-- 
```

### 3. Configuración del Proyecto

1. **Crear archivo .env:**

   ```bash
   cp .env.example .env
   ```

2. **Editar .env con tus credenciales:**

   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=tu_contraseña
   DB_NAME=proyecto_taller
   ```

3. **Permisos de carpetas:**
   ```bash
   chmod 755 publico/
   chmod 755 configuracion/
   ```



     |

## 🔑 Características Principales

### Autenticación

- Login de usuarios (Cliente/Contador)
- Registro de nuevos usuarios
- Manejo de sesiones seguras
- Bloqueo temporal después de 3 intentos fallidos

### Panel del Cliente

- Dashboard personalizado
- Carga de documentos
- Consulta de reportes
- Chat con asesor

### Panel del Contador

- Dashboard de estadísticas
- Registro de ingresos y egresos
- Gestión de impuestos
- Generación de reportes
- Selección de cliente para contabilización

## 🏗️ Mejoras Implementadas

✅ **Seguridad:**

- Credenciales centralizadas (no hardcodeadas)
- Prepared statements en todas las queries
- Validación en servidor
- Hasheo de contraseñas (PASSWORD_DEFAULT)

✅ **Organización:**

- Estructura MVC clara
- Separación de responsabilidades
- Código modular y reutilizable

✅ **Performance:**

- CSS consolidado (menos requests)
- JavaScript modular
- BD con singleton pattern

✅ **Mantenibilidad:**

- Nombres de carpetas en español
- Código comentado
- Estructura escalable

## 📝 Uso de la Aplicación

### Como Cliente

1. Registrarse en `vistas/autenticacion/registro.php`
2. Iniciar sesión en `vistas/autenticacion/inicio_sesion.php`
3. Acceder al panel en `vistas/cliente/panel_principal.php`

### Como Contador

1. Registrarse como "Contador"
2. Iniciar sesión
3. Acceder al panel en `vistas/contador/panel_principal.php`
4. Seleccionar cliente y gestionar su contabilidad

## 🔐 Notas de Seguridad

**IMPORTANTE:**

- No versiones el archivo `.env` en Git
- Cambia las contraseñas por defecto
- En producción, usa HTTPS
- Implementa rate limiting
- Añade CSRF tokens en formularios
- Valida entrada en servidor (nunca en cliente)

## 📞 Soporte

Para reportar problemas o sugerencias, contacta al equipo de desarrollo.

---

**Última actualización:** Abril 2026
**Versión:** 1.0
