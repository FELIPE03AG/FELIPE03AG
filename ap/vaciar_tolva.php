<?php
include("config.php");

if (isset($_GET['tolva'])) {
    $tolvaId = intval($_GET['tolva']);

    // Vaciar la tolva
    $query_tolva = "UPDATE tolvas SET 
                        num_alim = NULL,
                        etapa_alim = NULL,
                        fecha_alim = NULL
                    WHERE id = $tolvaId";
    $resultado_tolva = mysqli_query($conexion, $query_tolva);

    if ($resultado_tolva) {
        session_start();
        $usuario = $_SESSION['nombre'];

        // Historial
        $accion = "Vació la tolva con ID: $tolvaId";
        $fecha_hora = date('Y-m-d H:i:s');
        $registro = "INSERT INTO historial (accion, fecha_hora, usuario) 
                     VALUES ('$accion', '$fecha_hora', '$usuario')";
        mysqli_query($conexion, $registro);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al vaciar la tolva.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de tolva no proporcionado.']);
}
?>
