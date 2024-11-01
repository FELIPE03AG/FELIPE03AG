<?php
require_once "config.php";

if (!isset($_GET['idRegistro'])) {
    echo json_encode([
        'msg' => 'ID no enviado'
    ]);
    exit();
}

$idRegistro = $_GET['idRegistro'];

$sqlObtenerDetalleCorral = "SELECT CO.id_corral, CO.num_corral, CO.num_cerdos FROM cerdos as C JOIN corrales as CO on C.id_registro = CO.num_caseta WHERE C.id_registro = ?;";
$stmtCorrales = $conexion->prepare($sqlObtenerDetalleCorral);
$stmtCorrales->bind_param("i", $idRegistro);
$stmtCorrales->execute();

$data = $stmtCorrales->get_result();
$data = $data->fetch_all(MYSQLI_ASSOC);


echo json_encode($data);

