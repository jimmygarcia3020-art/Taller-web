# 📋 Requerimientos Funcionales del Sistema

Este documento define los requisitos funcionales del sistema contable **Contaplus**.

Los requerimientos describen qué funcionalidades debe cumplir el sistema para satisfacer las necesidades del usuario y sirven como base para el desarrollo, validación y pruebas.

---

## 👤 Gestión de usuarios

El sistema debe:

* Permitir el registro de usuarios.
* Permitir el inicio de sesión.
* Validar las credenciales de acceso.
* Permitir cerrar sesión de forma segura.

---

## 💰 Registro de ingresos y gastos

El sistema debe:

* Permitir registrar ingresos.
* Permitir registrar gastos.
* Permitir editar registros financieros.
* Permitir eliminar registros financieros.
* Clasificar ingresos y gastos por categoría.
* Almacenar la información en la base de datos.

---

## 🧾 Cálculo de impuestos

El sistema debe:

* Calcular automáticamente los impuestos en base a los ingresos y gastos registrados.
* Permitir configurar tipos de impuestos.
* Mostrar el resumen de impuestos por periodo.
* Actualizar los cálculos en tiempo real.

---

## 📊 Reportes

El sistema debe:

* Generar reportes de ingresos.
* Generar reportes de gastos.
* Generar reportes de impuestos.
* Permitir visualizar reportes por rango de fechas.
* Permitir exportar reportes en formatos como PDF o Excel.

---

## 🧠 Consideraciones de implementación

* Todos los requerimientos deben respetar la arquitectura MVC.
* Las operaciones deben validar datos en servidor.
* Los datos deben persistirse en la base de datos.
* Se debe garantizar consistencia e integridad de la información.
* Cada requerimiento debe poder mapearse a:

  * Controlador
  * Servicio (si aplica)
  * Modelo

---

## 📌 Relación con otros documentos

* Flujos del sistema: `docs/procesos.md`
* Contexto del negocio: `docs/negocio.md`
* Arquitectura: `docs/arquitectura.md`
* Guía general: `README.md`
