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
    header("Location: alimento.php");
    exit();
}

// Validar id
if (!isset($_POST['id']) || $_POST['id'] === '') {
    header("Location: alimento.php?msg=invalid");
    exit();
}

$id = intval($_POST['id']);
$usuario = $_SESSION['nombre'];

try {
    // Iniciar transacción
    $conexion->begin_transaction();

    // Preparar DELETE
    $stmt = $conexion->prepare("DELETE FROM tolvas WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Error prepare DELETE: " . $conexion->error);
    }
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new Exception("Error execute DELETE: " . $err);
    }

    // Comprobar que se haya eliminado una fila
    if ($stmt->affected_rows === 0) {
        $stmt->close();
        $conexion->rollback();
        header("Location: alimento.php?msg=notfound");
        exit();
    }

    $stmt->close();

    // Insertar en historial
    $accion = "Eliminó registro de Tolva";
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

    // Commit si todo salió bien
    $conexion->commit();

    header("Location: alimento.php?msg=eliminado");
    exit();

} catch (Exception $e) {
    // Rollback y log
    if ($conexion->connect_errno === 0) { // solo si la conexión está bien
        $conexion->rollback();
    }
    error_log("eliminar_alimento.php error: " . $e->getMessage());
    header("Location: alimento.php?msg=error");
    exit();
}
