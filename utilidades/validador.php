<?php
/**
 * Utilidades de validación
 * Reemplaza a: validar.js + validar1.js (lado servidor)
 * 
 * Centraliza validaciones de datos
 */

class Validador {
    
    /**
     * Validar email
     */
    public static function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validar que no esté vacío
     */
    public static function validarNoVacio($valor, $nombre = 'Campo') {
        if (empty(trim($valor))) {
            return ['valido' => false, 'error' => "$nombre es obligatorio"];
        }
        return ['valido' => true];
    }
    
    /**
     * Validar longitud mínima
     */
    public static function validarLongitudMinima($valor, $minimo, $nombre = 'Campo') {
        if (strlen(trim($valor)) < $minimo) {
            return ['valido' => false, 'error' => "$nombre debe tener mínimo $minimo caracteres"];
        }
        return ['valido' => true];
    }
    
    /**
     * Validar longitud máxima
     */
    public static function validarLongitudMaxima($valor, $maximo, $nombre = 'Campo') {
        if (strlen(trim($valor)) > $maximo) {
            return ['valido' => false, 'error' => "$nombre debe tener máximo $maximo caracteres"];
        }
        return ['valido' => true];
    }
    
    /**
     * Validar que sea numérico
     */
    public static function validarNumerico($valor, $nombre = 'Campo') {
        if (!is_numeric($valor)) {
            return ['valido' => false, 'error' => "$nombre debe ser un número"];
        }
        return ['valido' => true];
    }
    
    /**
     * Validar teléfono
     */
    public static function validarTelefono($telefono, $nombre = 'Teléfono') {
        if (strlen(trim($telefono)) < 7 || strlen(trim($telefono)) > 15) {
            return ['valido' => false, 'error' => "$nombre inválido"];
        }
        if (!is_numeric($telefono)) {
            return ['valido' => false, 'error' => "$nombre debe contener solo números"];
        }
        return ['valido' => true];
    }
    
    /**
     * Validar formulario de registro de usuario
     */
    public static function validarRegistroUsuario($datos) {
        $errores = [];
        
        // Nombre
        $valdNombre = self::validarNoVacio($datos['nombre_contacto'] ?? '', 'Nombre');
        if (!$valdNombre['valido']) $errores[] = $valdNombre['error'];
        
        $valdNombreLong = self::validarLongitudMaxima($datos['nombre_contacto'] ?? '', 30, 'Nombre');
        if (!$valdNombreLong['valido']) $errores[] = $valdNombreLong['error'];
        
        // Negocio
        $valdNegocio = self::validarNoVacio($datos['nombre_negocio'] ?? '', 'Nombre de negocio');
        if (!$valdNegocio['valido']) $errores[] = $valdNegocio['error'];
        
        $valdNegocioLong = self::validarLongitudMaxima($datos['nombre_negocio'] ?? '', 80, 'Nombre de negocio');
        if (!$valdNegocioLong['valido']) $errores[] = $valdNegocioLong['error'];
        
        // Teléfono
        $valdTelefono = self::validarNoVacio($datos['numero_contacto'] ?? '', 'Teléfono');
        if (!$valdTelefono['valido']) $errores[] = $valdTelefono['error'];
        
        $valdTelefonoNum = self::validarTelefono($datos['numero_contacto'] ?? '');
        if (!$valdTelefonoNum['valido']) $errores[] = $valdTelefonoNum['error'];
        
        // Correo
        $valdCorreo = self::validarNoVacio($datos['correo'] ?? '', 'Correo');
        if (!$valdCorreo['valido']) $errores[] = $valdCorreo['error'];
        
        $valdCorreoEmail = self::validarEmail($datos['correo'] ?? '');
        if (!$valdCorreoEmail) $errores[] = 'Correo inválido';
        
        $valdCorreoLong = self::validarLongitudMaxima($datos['correo'] ?? '', 100, 'Correo');
        if (!$valdCorreoLong['valido']) $errores[] = $valdCorreoLong['error'];
        
        // Clave
        $valdClave = self::validarNoVacio($datos['clave'] ?? '', 'Clave');
        if (!$valdClave['valido']) $errores[] = $valdClave['error'];
        
        $valdClaveLong = self::validarLongitudMaxima($datos['clave'] ?? '', 20, 'Clave');
        if (!$valdClaveLong['valido']) $errores[] = $valdClaveLong['error'];
        
        return [
            'valido' => count($errores) === 0,
            'errores' => $errores
        ];
    }
    
    /**
     * Validar formulario de inicio de sesión
     */
    public static function validarInicioSesion($datos) {
        $errores = [];
        
        // Correo
        $valdCorreo = self::validarNoVacio($datos['correo'] ?? '', 'Correo');
        if (!$valdCorreo['valido']) $errores[] = $valdCorreo['error'];
        
        if (!empty($datos['correo'])) {
            $valdCorreoEmail = self::validarEmail($datos['correo'] ?? '');
            if (!$valdCorreoEmail) $errores[] = 'Correo inválido';
        }
        
        // Clave
        $valdClave = self::validarNoVacio($datos['clave'] ?? '', 'Clave');
        if (!$valdClave['valido']) $errores[] = $valdClave['error'];
        
        return [
            'valido' => count($errores) === 0,
            'errores' => $errores
        ];
    }
}
?>
