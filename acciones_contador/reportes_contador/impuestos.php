<?php
header('Content-Type: application/json');
$conexion = new mysqli("localhost", "root", "73442998", "proyecto_taller");

$tipo = $_GET["tipo"];

switch ($tipo) {

    case "impuesto_generado":
        $sql = "SELECT SUM(dc.subtotal * (i.tasa / 100)) AS impuesto_generado
                FROM detalle_comprobante dc
                INNER JOIN impuestos i ON i.id_impuesto = 1";
        break;

    case "credito_fiscal":
        $sql = "SELECT SUM(co.total * (i.tasa / 100)) AS impuesto_credito
                FROM comprobantes co
                INNER JOIN impuestos i ON i.id_impuesto = 1
                WHERE co.tipo = 'FACTURA'";
        break;

    case "impuestos_periodo":
        $sql = "SELECT DATE_FORMAT(co.fecha, '%Y-%m') AS periodo,
                       SUM(co.total * (i.tasa / 100)) AS impuesto_total
                FROM comprobantes co
                INNER JOIN impuestos i ON i.id_impuesto = 1
                GROUP BY periodo";
        break;

    case "tasas":
        $sql = "SELECT tipo, i.nombre AS impuesto, tasa FROM impuestos i";
        break;
}

$result = $conexion->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
