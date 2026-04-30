# 📊 Sistema de Gestión Contable - Contaplus

Sistema web orientado a la gestión contable de pequeñas y medianas empresas, diseñado para registrar, analizar y organizar información financiera de manera eficiente.

El sistema permite administrar ingresos, egresos, impuestos y reportes financieros, facilitando la toma de decisiones mediante una interfaz clara y estructurada.

---

## 🚀 Tecnologías

* Backend: PHP
* Base de datos: MySQL
* Arquitectura: MVC
* Frontend: HTML, CSS, JavaScript

---

## 🧩 Funcionalidades principales

* Gestión de usuarios (registro, login, sesiones)
* Registro de ingresos y egresos
* Cálculo de impuestos
* Generación de reportes financieros
* Paneles diferenciados (cliente / contador)

---

## 📁 Estructura del proyecto

```
Taller-web/
├── publico/
├── vistas/
├── controladores/
├── modelos/
├── api/
├── utilidades/
├── configuracion/
├── acciones/
```

---

## ⚙️ Configuración rápida

### 1. Base de datos

```sql
CREATE DATABASE proyecto_taller;
```

### 2. Variables de entorno

Crear archivo `.env`:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=tu_contraseña
DB_NAME=proyecto_taller
```

---

## 🧠 Contexto del sistema (para IA y desarrollo)

El sistema maneja un dominio contable básico compuesto por:

* Transacciones financieras (ingresos y egresos)
* Cálculo de impuestos basado en registros
* Generación de reportes por periodo
* Gestión de múltiples usuarios con roles

### Reglas clave del dominio

* Toda transacción debe estar asociada a un usuario
* Los impuestos se calculan a partir de ingresos y egresos
* Los reportes se generan en base a rangos de fechas
* Los datos deben persistirse en base de datos

---

## 🏗️ Convenciones del proyecto

* Separación estricta de responsabilidades (MVC)
* Controladores manejan solicitudes
* Modelos gestionan datos
* Vistas representan la interfaz
* Validación siempre en servidor

---

## 🔐 Seguridad

* Uso de prepared statements
* Hash de contraseñas
* Manejo seguro de sesiones
* Variables sensibles en `.env`

---

## 📌 Estado del proyecto

En desarrollo activo.

Funcionalidades base implementadas, con mejoras planificadas en el roadmap del proyecto.

---

## 📅 Versión

1.0 (Abril 2026)
