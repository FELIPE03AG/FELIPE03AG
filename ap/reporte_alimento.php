<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Consulta a la base de datos con el rango
    $query = "SELECT * FROM tolvas 
              WHERE DATE(fecha_llegada_alim) BETWEEN '$fecha_inicio' AND '$fecha_fin'
              ORDER BY fecha_llegada_alim ASC";
    $result = mysqli_query($conexion, $query);

    // Armar contenido HTML
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

    $html .= "</tbody></table>";

    // Enviar al navegador como PDF (truco: usar cabecera application/pdf)
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=reporte_alimento.pdf");

    // Usar la librería nativa de impresión de PDF del navegador
    // Convertimos HTML → PDF por medio de print del navegador (si servidor no soporta dompdf)
    // Método alterno simple: enviar como word/pdf embebido
    echo "<script> 
            var ventana = window.open('', '_blank');
            ventana.document.write(`$html`);
            ventana.document.close();
            ventana.print();
          </script>";
    exit;
} else {
    echo "Acceso inválido";
}
