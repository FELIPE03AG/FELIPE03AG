<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos requeridos están presentes
    if (!isset($_POST['num_alim'], $_POST['fecha_alim'], $_POST['etapa_alim'])) {
        die("Faltan campos requeridos en el formulario");
    }

    $num_alim = $_POST['num_alim'];
    $fecha_alim = $_POST['fecha_alim'];
    $etapa_alim = $_POST['etapa_alim'];

    // Depuración: Mostrar los valores recibidos
    echo "<pre>Datos recibidos:\n";
    print_r($_POST);
    echo "</pre>";

    // Preparar la consulta SQL
    $sql = "INSERT INTO alimentos (num_alim, fecha_alim, etapa_alim) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iss", $num_alim, $fecha_alim, $etapa_alim);
        
        if ($stmt->execute()) {
            echo "Inserción exitosa! Redireccionando...";
            header("Location: alimentos.php");
            exit();
        } else {
            echo "<h3>Error al ejecutar la consulta:</h3>";
            echo "<p>" . $stmt->error . "</p>";
            echo "<h3>Consulta SQL:</h3>";
            echo "<p>" . $sql . "</p>";
        }
        
        $stmt->close();
    } else {
        echo "<h3>Error al preparar la consulta:</h3>";
        echo "<p>" . $conn->error . "</p>";
    }

    $conn->close();
} else {
    echo "Método de solicitud no permitido";
}
?>