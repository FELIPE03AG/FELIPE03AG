<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['nombre'])) {
    header('Location: index.php');
    exit();
}

include("config.php"); // Debe definir $conexion (mysqli)

// Verificar método
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: vacunacion.php");
    exit();
}

// Validar id
if (!isset($_POST['id']) || $_POST['id'] === '') {
    header("Location: vacunacion.php?msg=invalid");
    exit();
}

$id = intval($_POST['id']);
$usuario = $_SESSION['nombre'];

try {
    // Iniciar transacción
    $conexion->begin_transaction();

    // 🔹 1. Obtener datos antes de eliminar
    $stmtSelect = $conexion->prepare("SELECT nombre, num_caseta FROM vacunas WHERE id = ?");
    if (!$stmtSelect) {
        throw new Exception("Error prepare SELECT: " . $conexion->error);
    }
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $resultado = $stmtSelect->get_result();

    if ($resultado->num_rows === 0) {
        // No existe el registro
        $stmtSelect->close();
        $conexion->rollback();
        header("Location: vacunacion.php?msg=notfound");
        exit();
    }

    $fila = $resultado->fetch_assoc();
    $nombre = $fila['nombre'];
    $num_caseta = $fila['num_caseta'];
    $stmtSelect->close();

    // 🔹 2. Eliminar registro
    $stmtDelete = $conexion->prepare("DELETE FROM vacunas WHERE id = ?");
    if (!$stmtDelete) {
        throw new Exception("Error prepare DELETE: " . $conexion->error);
    }
    $stmtDelete->bind_param("i", $id);
    if (!$stmtDelete->execute()) {
        $err = $stmtDelete->error;
        $stmtDelete->close();
        throw new Exception("Error execute DELETE: " . $err);
    }

    if ($stmtDelete->affected_rows === 0) {
        $stmtDelete->close();
        $conexion->rollback();
        header("Location: vacunacion.php?msg=notfound");
        exit();
    }
    $stmtDelete->close();

    // 🔹 3. Registrar acción en historial
    $accion = "Eliminó la vacuna '{$nombre}' de la caseta: {$num_caseta}";
    $fecha_hora = date('Y-m-d H:i:s');

    $stmtHist = $conexion->prepare("INSERT INTO historial (accion, fecha_hora, usuario) VALUES (?, ?, ?)");
    if (!$stmtHist) {
        throw new Exception("Error prepare HIST: " . $conexion->error);
    }
    $stmtHist->bind_param("sss", $accion, $fecha_hora, $usuario);
    if (!$stmtHist->execute()) {
        $err = $stmtHist->error;
        $stmtHist->close();
        throw new Exception("Error execute HIST: " . $err);
    }
    $stmtHist->close();

    // 🔹 4. Confirmar cambios
    $conexion->commit();

    header("Location: vacunacion.php?msg=eliminado");
    exit();

} catch (Exception $e) {
    // Rollback y log
    if ($conexion->connect_errno === 0) {
        $conexion->rollback();
    }
    error_log("eliminar_vacuna.php error: " . $e->getMessage());
    header("Location: vacunacion.php?msg=error");
    exit();
}
?>
