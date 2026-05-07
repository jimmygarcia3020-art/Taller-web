-- ==============================================================================
-- SISTEMA CONTABLE - SCRIPT MAESTRO DE BASE DE DATOS
-- Versión: 2.0 (Estructura optimizada, relacional y con soporte para IA/Roadmap)
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `proyecto_taller`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `proyecto_taller`;

-- ==========================================
-- 1. TABLAS DE ACCESO Y CONFIGURACIÓN
-- ==========================================

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `tipo_usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `datos_registro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_contacto` varchar(100) DEFAULT NULL,
  `nombre_negocio` varchar(100) DEFAULT NULL,
  `numero_contacto` varchar(20) DEFAULT NULL,
  `tipo_usuario` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `factura` varchar(50) DEFAULT NULL,
  `regimen` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_datos_registro_correo` (`correo`),
  CONSTRAINT `fk_datos_registro_correo` FOREIGN KEY (`correo`) REFERENCES `usuarios` (`correo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- ==========================================
-- 2. TABLAS MAESTRAS (CATÁLOGOS)
-- ==========================================

CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo_cliente` enum('NATURAL','JURIDICO') DEFAULT 'NATURAL',
  `ruc_dni` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `ruc_dni` (`ruc_dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`),
  UNIQUE KEY `ruc` (`ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `productos_servicios` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('PRODUCTO','SERVICIO') NOT NULL,
  `precio` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `plan_cuentas` (
  `id_cuenta` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('ACTIVO','PASIVO','PATRIMONIO','INGRESO','GASTO') NOT NULL,
  PRIMARY KEY (`id_cuenta`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('INGRESO','EGRESO') NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- ==========================================
-- 3. TABLAS TRANSACCIONALES CORE
-- ==========================================

CREATE TABLE IF NOT EXISTS `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `doc` varchar(50) DEFAULT NULL,
  `entidad` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `monto` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `compras_cliente_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `doc` varchar(50) DEFAULT NULL,
  `entidad` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `monto` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `ventas_cliente_fk` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `impuestos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `periodo` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pagado',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_impuestos_cliente` (`id_cliente`),
  CONSTRAINT `fk_impuestos_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `transacciones` (
  `id_transaccion` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_categoria` int DEFAULT NULL,
  `tipo` enum('INGRESO','EGRESO') NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `descripcion` text,
  `fecha` date NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaccion`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `fk_trans_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  CONSTRAINT `fk_trans_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- ==========================================
-- 4. TABLAS DE COMPROBANTES Y ASIENTOS
-- ==========================================

CREATE TABLE IF NOT EXISTS `comprobantes` (
  `id_comprobante` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_proveedor` int DEFAULT NULL,
  `tipo` enum('FACTURA','BOLETA','RECIBO','OTRO') NOT NULL,
  `numero` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `estado` enum('PENDIENTE','PAGADO','ANULADO') DEFAULT 'PENDIENTE',
  PRIMARY KEY (`id_comprobante`),
  UNIQUE KEY `numero` (`numero`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_proveedor` (`id_proveedor`),
  CONSTRAINT `comprobantes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT,
  CONSTRAINT `comprobantes_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `detalle_comprobante` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_comprobante` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `id_comprobante` (`id_comprobante`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `detalle_comprobante_ibfk_1` FOREIGN KEY (`id_comprobante`) REFERENCES `comprobantes` (`id_comprobante`) ON DELETE CASCADE,
  CONSTRAINT `detalle_comprobante_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos_servicios` (`id_producto`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `pagos` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_comprobante` int NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `metodo_pago` enum('EFECTIVO','TRANSFERENCIA','TARJETA') NOT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `id_comprobante` (`id_comprobante`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_comprobante`) REFERENCES `comprobantes` (`id_comprobante`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `asientos` (
  `id_asiento` int NOT NULL AUTO_INCREMENT,
  `id_comprobante` int DEFAULT NULL,
  `id_cuenta` int NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `debe` decimal(12,2) DEFAULT '0.00',
  `haber` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id_asiento`),
  KEY `id_comprobante` (`id_comprobante`),
  KEY `id_cuenta` (`id_cuenta`),
  CONSTRAINT `asientos_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `plan_cuentas` (`id_cuenta`) ON DELETE RESTRICT,
  CONSTRAINT `asientos_ibfk_2` FOREIGN KEY (`id_comprobante`) REFERENCES `comprobantes` (`id_comprobante`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- ==========================================
-- 5. TABLAS Y TRIGGERS DE AUDITORÍA
-- ==========================================

CREATE TABLE IF NOT EXISTS `auditoria` (
  `id_auditoria` int NOT NULL AUTO_INCREMENT,
  `tabla_afectada` varchar(50) NOT NULL,
  `id_registro` int NOT NULL,
  `accion` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_auditoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Triggers de Auditoría Automatizados
DELIMITER //

CREATE TRIGGER IF NOT EXISTS `auditoria_nueva_venta` AFTER INSERT ON `ventas`
FOR EACH ROW
BEGIN
    INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario`, `fecha`)
    VALUES ('ventas', NEW.id, 'INSERT', 'Sistema', NOW());
END;
//

CREATE TRIGGER IF NOT EXISTS `auditoria_nueva_compra` AFTER INSERT ON `compras`
FOR EACH ROW
BEGIN
    INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario`, `fecha`)
    VALUES ('compras', NEW.id, 'INSERT', 'Sistema', NOW());
END;
//

DELIMITER ;