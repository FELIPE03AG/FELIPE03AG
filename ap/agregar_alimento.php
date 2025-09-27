<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_caseta = $_POST['num_caseta'];
    $cantidad = $_POST['cantidad'];
    $etapa = $_POST['etapa'];

    $sql = "INSERT INTO tolvas (num_tolva, cantidad_alim, etapa_alim, fecha_llegada_alim) 
            VALUES ('$num_caseta', '$cantidad', '$etapa', NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: alimento.php?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
3️⃣ eliminar_alimento.php
php
Copiar código
<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "DELETE FROM tolvas WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: alimento.php?deleted=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>