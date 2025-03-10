<?php
// guardar_cerdos.php
include 'config.php';

$caseta = $_POST['caseta'];
$num_cerdos = $_POST['num_cerdos'];
$peso_prom = $_POST['peso_prom'];
$edad_prom = $_POST['edad_prom'];
$fecha_llegada = $_POST['fecha_llegada'];
$etapa = $_POST['etapa'];

try {
    $sql = "INSERT INTO cerdos (num_caseta, num_cerdos, peso_prom, edad_prom, fecha_llegada_cerdos, etapa_inicial) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$caseta, $num_cerdos, $peso_prom, $edad_prom, $fecha_llegada, $etapa]);

    echo "<script>alert('Registro guardado exitosamente.'); window.location.href = 'admin_cerdos.html';</script>";
} catch (PDOException $e) {
    echo "<script>alert('Error al guardar: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>
