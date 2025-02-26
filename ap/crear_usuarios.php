<?php
include("config.php"); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol = strtolower(trim($_POST['rol'])); // Convertir rol a minúsculas
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($nombre) || empty($correo) || empty($rol) || empty($password)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    // Validar que el rol sea "admin" o "user"
    if ($rol !== 'admin' && $rol !== 'user') {
        $rol = 'user'; // Si el rol es inválido, se asigna "user"
    }

    // Verificar si el usuario o correo ya existen en la base de datos
    $sql_check = "SELECT u FROM usuarios WHERE u = ? OR co = ?";
    if ($stmt_check = $conexion->prepare($sql_check)) {
        $stmt_check->bind_param("ss", $usuario, $correo);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<script>alert('El usuario o correo ya existen.'); window.history.back();</script>";
            $stmt_check->close();
            $conexion->close();
            exit();
        }
        $stmt_check->close();
    }

    // Encriptar la contraseña con SHA1
    $password_encriptada = sha1($password);

    // Insertar el nuevo usuario en la base de datos
    $sql_insert = "INSERT INTO usuarios (u, nombre, co, rol, c) VALUES (?, ?, ?, ?, ?)";
    if ($stmt_insert = $conexion->prepare($sql_insert)) {
        $stmt_insert->bind_param("sssss", $usuario, $nombre, $correo, $rol, $password_encriptada);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Usuario agregado correctamente'); window.location.href='administrar_usuarios.php';</script>";
        } else {
            echo "Error al agregar usuario: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    } else {
        echo "Error en la consulta SQL.";
    }

    $conexion->close();
} else {
    echo "Acceso no autorizado.";
}
?>
