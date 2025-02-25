<?php
include("config.php"); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $usuario = trim($_POST['usuario']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol = trim($_POST['rol']);
    $password = trim($_POST['password']);

    // Validar que no haya campos vacíos
    if (empty($usuario) || empty($nombre) || empty($correo) || empty($rol) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }


    // Verificar que el rol sea válido ('admin' o 'user')
    if ($rol !== 'admin' && $rol !== 'user') {
        $rol = 'user'; // Seguridad adicional en caso de valores inválidos
    }

    // Encriptar la contraseña con SHA1
    $password_encriptada = sha1($password);

    //Validacion de que no se repita usuario ni correo

    // Preparar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (u, nombre, co, rol, c) VALUES (?, ?, ?, ?, ?)";

    // Usar prepared statements para evitar SQL Injection
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("sssss", $usuario, $nombre, $correo, $rol, $password_encriptada);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario agregado correctamente'); window.location.href='administrar_usuarios.php';</script>";
        } else {
            echo "Error al agregar usuario: " . $stmt->error;
        }

        // Cerrar la consulta
        $stmt->close();
    } else {
        echo "Error en la consulta SQL.";
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo "Acceso no autorizado.";
}
?>

