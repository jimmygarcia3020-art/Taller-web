<?php
/**
 * Clase para manejo de base de datos (Singleton)
 */

class BaseDatos {
    private static $instancia = null;
    private $conexion;
    
    /**
     * Constructor privado (Patrón Singleton)
     */
    private function __construct() {
        $this->conectar();
    }
    
    /**
     * Conectar a la base de datos
     */
    private function conectar() {
        $this->conexion = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME
        );
        
        if ($this->conexion->connect_error) {
            die(json_encode(["error" => "Error de conexión: " . $this->conexion->connect_error]));
        }
        
        $this->conexion->set_charset("utf8mb4");
    }
    
    /**
     * Obtener instancia (Patrón Singleton)
     */
    public static function obtenerInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }
    
    /**
     * Obtener conexión
     */
    public function getConexion() {
        return $this->conexion;
    }
    
    /**
     * Ejecutar query preparada
     */
    public function ejecutar($sql, $tipos, $parametros) {
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return ['error' => 'Error en prepare: ' . $this->conexion->error];
        }
        
        $stmt->bind_param($tipos, ...$parametros);
        
        if (!$stmt->execute()) {
            return ['error' => 'Error en execute: ' . $stmt->error];
        }
        
        return ['success' => true, 'stmt' => $stmt];
    }
    
    /**
     * Cerrar puertas (aunque normalmente se cierra automáticamente)
     */
    public function cerrar() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>
