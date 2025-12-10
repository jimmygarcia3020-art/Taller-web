<?php
header('Content-Type: application/json');
$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión"]));
}

$tipo = $_GET["tipo"];

switch ($tipo) {

    case "pagos_mes":
        $sql = "SELECT DATE_FORMAT(fecha_pago, '%Y-%m') AS mes, SUM(monto) AS total_pagado 
                FROM pagos GROUP BY mes ORDER BY mes";
        break;

    case "metodos_pago":
        $sql = "SELECT metodo_pago, SUM(monto) AS total FROM pagos GROUP BY metodo_pago";
        break;

    case "pagos_cliente":
        $sql = "SELECT c.nombre AS cliente, SUM(p.monto) AS total_pagado
                FROM pagos p
                INNER JOIN comprobantes co ON p.id_comprobante = co.id_comprobante
                INNER JOIN clientes c ON co.id_cliente = c.id_cliente
                GROUP BY c.id_cliente";
        break;

    case "pagos_proveedor":
        $sql = "SELECT pr.nombre AS proveedor, SUM(p.monto) AS total_pagado
                FROM pagos p
                INNER JOIN comprobantes co ON p.id_comprobante = co.id_comprobante
                INNER JOIN proveedores pr ON co.id_proveedor = pr.id_proveedor
                GROUP BY pr.id_proveedor";
        break;

    case "pendientes":
        $sql = "SELECT co.id_comprobante, co.numero, co.fecha, co.total, c.nombre
                FROM comprobantes co
                INNER JOIN clientes c ON co.id_cliente = c.id_cliente
                WHERE co.estado = 'PENDIENTE'";
        break;
}

$result = $conexion->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
