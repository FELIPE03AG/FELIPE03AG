<?php
include("config.php");

// Capturar rango de fechas
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];

// Consultar registros en el rango
$query = "SELECT * FROM tolvas 
          WHERE DATE(fecha_llegada_alim) BETWEEN '$fecha_inicio' AND '$fecha_fin'
          ORDER BY fecha_llegada_alim ASC";
$result = mysqli_query($conexion, $query);

// HTML del reporte
$html = "
<h2 style='text-align:center;'>Reporte de Alimento</h2>
<p><b>Rango de Fechas:</b> $fecha_inicio a $fecha_fin</p>
<table border='1' cellspacing='0' cellpadding='5' width='100%'>
    <thead>
        <tr style='background:#ddd;'>
            <th>ID</th>
            <th>Etapa</th>
            <th>Cantidad (kg)</th>
            <th>Fecha Llegada</th>
            <th>Número de Tolva</th>
        </tr>
    </thead>
    <tbody>";
while ($row = mysqli_fetch_assoc($result)) {
    $html .= "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['etapa_alim']}</td>
            <td>{$row['cantidad_alim']}</td>
            <td>{$row['fecha_llegada_alim']}</td>
            <td>{$row['num_tolva']}</td>
        </tr>";
}
$html .= "
    </tbody>
</table>";

// Generar PDF (nativo sin librerías externas)
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=reporte_alimento.doc");
echo $html;
?>
