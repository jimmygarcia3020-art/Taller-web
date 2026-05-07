Aquí tienes el texto estructurado y formateado en Markdown, optimizado para su lectura e implementación técnica:

---

# 🎨 Sistema de Diseño Visual y Paleta de Colores

## 1. Composición Visual
La composición visual se apoya en una base neutra de **grises suaves**, **blancos** y **tonos azulados claros**, sobre la cual destaca un **azul corporativo** como color primario de acento e identidad de marca.

El color predominante en términos de impacto visual es el azul corporativo `#2E5EAA`.

Si bien los tonos grises presentan una alta prevalencia en superficie (utilizados en fondos, divisores, textos secundarios y contenedores), el azul se establece como el color fundamental que cumple las siguientes funciones clave:

*   🎯 Dirige la atención del usuario.
*   🏛️ Cimenta la identidad institucional del sistema.
*   🔘 Resalta los botones primarios.
*   🧭 Indica el estado de navegación activa.
*   🃏 Enfatiza las tarjetas informativas cruciales.
*   📣 Señala las llamadas a la acción (CTAs).
*   👁️ Optimiza la orientación visual a través de los distintos módulos.

---

## 2. Nomenclatura: Tokens de Diseño Semánticos
La nomenclatura aplicada corresponde a un esquema de **Tokens de Diseño Semánticos**. Este enfoque no nombra los colores únicamente por su valor hexadecimal, sino por la **función** que cumplen dentro de la interfaz.

> **Beneficio:** Esto mejora la escalabilidad del sistema visual y facilita su implementación en CSS, Tailwind, Bootstrap o cualquier Design System.

### Tokens Principales Propuestos

| Token | Valor Hex | Uso Principal |
| :--- | :--- | :--- |
| `color-brand-primario` | `#2E5EAA` | Botones CTA, enlaces importantes, sidebar activa, encabezados clave, tarjetas destacadas. |
| `color-surface-base` | `#F8FAFC` | Fondo general de vistas y zonas de descanso visual. |
| `color-surface-card` | `#FFFFFF` | Tarjetas, formularios, tablas y módulos. |
| `color-texto-primario` | `#1F2937` | Títulos, KPIs, datos financieros, navegación. |
| `color-texto-secundario` | `#727785` | Descripciones, labels, ayuda contextual y textos de soporte. |
| `color-border-sutil` | `#E5E7EB` | Líneas divisorias, inputs, tablas y paneles. |

---

## 3. Colores Semánticos Funcionales
El sistema utiliza colores auxiliares con nomenclatura funcional para representar estados del sistema.

| Estado | Token | Valor Hex | Significado / Uso |
| :--- | :--- | :--- | :--- |
| ✅ **Éxito** | `color-exito` | `#22C55E` | Aprobado, al día, correcto, sincronizado. |
| ⚠️ **Advertencia** | `color-advertencia` | `#F59E0B` | Pendiente, requiere atención, próximo vencimiento. |
| ❌ **Error** | `color-peligro` | `#EF4444` | Vencido, rechazo, inconsistencias. |
| ℹ️ **Información** | `color-info` | `#60A5FA` | Reportes, documentos, ayuda contextual. |

---

## 4. Justificación UX
La selección cromática responde a criterios de **usabilidad intergeneracional**, equilibrando estética moderna con accesibilidad cognitiva.

### Para usuarios jóvenes
*   Transmite modernidad.
*   Refuerza la sensación tecnológica.
*   Mantiene una estética profesional tipo SaaS.
*   Favorece dashboards visuales y métricas claras.

### Para adultos mayores
*   Alto contraste entre azul, blanco y gris.
*   Botones claramente identificables.
*   Separación visual fuerte entre módulos.
*   Menor fatiga visual gracias a los fondos neutros.
*   Colores de estado fácilmente distinguibles.

> **Conclusión:** Esta combinación hace que el sistema sea **profesional**, **confiable**, **legible** y **cognitivamente accesible** para ambos grupos demográficos.