<?php
ob_start();
include("config.php"); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario  = trim($_POST['usuario']);
    $nombre   = trim($_POST['nombre']);
    $correo   = trim($_POST['correo']);
    $rol      = strtolower(trim($_POST['rol']));
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($nombre) || empty($correo) || empty($rol) || empty($password)) {
        header("Location: administrar_usuarios.php?error=campos");
        exit();
    }

    // Validar que el rol sea "admin" o "user"
    if ($rol !== 'admin' && $rol !== 'user') {
        $rol = 'user';
    }

    // Verificar si el usuario o correo ya existen en la base de datos
    $sql_check = "SELECT u FROM usuarios WHERE u = ? OR co = ?";

    if ($stmt_check = $conexion->prepare($sql_check)) {
        $stmt_check->bind_param("ss", $usuario, $correo);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            $conexion->close();

            header("Location: administrar_usuarios.php?error=existe");
            exit();
        }

        $stmt_check->close();
    }

    // Encriptar la contraseña
    $password_encriptada = sha1($password);

    // Insertar el nuevo usuario
    $sql_insert = "INSERT INTO usuarios (u, nombre, co, rol, c) VALUES (?, ?, ?, ?, ?)";

    if ($stmt_insert = $conexion->prepare($sql_insert)) {
        $stmt_insert->bind_param("sssss", $usuario, $nombre, $correo, $rol, $password_encriptada);

        if ($stmt_insert->execute()) {

            header("Location: administrar_usuarios.php?success=1");
            exit();

        } else {
            header("Location: administrar_usuarios.php?error=bd");
            exit();
        }

        $stmt_insert->close();
    }

    $conexion->close();
} else {
    header("Location: administrar_usuarios.php?error=acceso");
    exit();
}
?>