<?php
// agregar_vacuna.php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location: index.php');
    exit();
}

include("config.php"); // debe definir $conexion (mysqli)

// Verificar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vacunacion.php?msg=invalid_method');
    exit();
}

if (!isset($_POST['num_caseta'], $_POST['nombre'], $_POST['fecha'])) {
    header('Location: vacunacion.php?msg=faltan_datos');
    exit();
}

// Sanitizar / preparar datos
$num_caseta = intval($_POST['num_caseta']);
$nombre = trim($_POST['nombre']);
$fecha_input = trim($_POST['fecha']); // puede venir 'YYYY-MM-DD' o 'YYYY-MM-DDTHH:MM' etc.

// Normalizar fecha a formato MySQL DATETIME 'YYYY-MM-DD HH:MM:SS'
if (strpos($fecha_input, 'T') !== false) {
    $fecha = date('Y-m-d H:i:s', strtotime($fecha_input));
} else {
    // si solo viene fecha, dejamos hora 00:00:00
    $fecha = date('Y-m-d H:i:s', strtotime($fecha_input . ' 00:00:00'));
}

// Validaciones básicas (ejemplo)
if ($num_caseta < 1 || $num_caseta > 9999) {
    header('Location: vacunacion.php?msg=caseta_invalida');
    exit();
}
if ($nombre === '') {
    header('Location: vacunacion.php?msg=nombre_invalido');
    exit();
}

// Iniciar transacción para asegurar integridad
$conexion->begin_transaction();

try {
    // 1) Insertar en tabla vacunas con prepared statement
    $stmtVac = $conexion->prepare("INSERT INTO vacunas (num_caseta, nombre, fecha) VALUES (?, ?, ?)");
    if (!$stmtVac) {
        throw new Exception("Prepare vacunas: " . $conexion->error);
    }
    $stmtVac->bind_param("iss", $num_caseta, $nombre, $fecha);
    if (!$stmtVac->execute()) {
        $err = $stmtVac->error;
        $stmtVac->close();
        throw new Exception("Execute vacunas: " . $err);
    }
    // opcional: obtener id insertado
    $insertedId = $stmtVac->insert_id;
    $stmtVac->close();

    // 2) Insertar en historial (preparado)
    $usuario = $_SESSION['nombre'];
    $fecha_hora = date('Y-m-d H:i:s');
    $accion = "Agregó una vacuna '{$nombre}' en la caseta: {$num_caseta}";

    $stmtHist = $conexion->prepare("INSERT INTO historial (accion, fecha_hora, usuario) VALUES (?, ?, ?)");
    if (!$stmtHist) {
        throw new Exception("Prepare historial: " . $conexion->error);
    }
    $stmtHist->bind_param("sss", $accion, $fecha_hora, $usuario);
    if (!$stmtHist->execute()) {
        $err = $stmtHist->error;
        $stmtHist->close();
        throw new Exception("Execute historial: " . $err);
    }
    $stmtHist->close();

    // Si todo ok, commit
    $conexion->commit();

    // Redirigir con mensaje de éxito
    header("Location: vacunacion.php?success=1");
    exit();

} catch (Exception $e) {
    // Rollback en caso de error
    $conexion->rollback();
    error_log("agregar_vacuna.php ERROR: " . $e->getMessage());

    // Redirigir con mensaje de error (puedes mostrar detalles en modo dev)
    header("Location: vacunacion.php?msg=error_agregar");
    exit();
}
