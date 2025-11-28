<?php
ob_start();
include("config.php");

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

    // Validar rol permitido
    if ($rol !== 'admin' && $rol !== 'user') {
        $rol = 'user';
    }

    $sql_user = "SELECT u FROM usuarios WHERE u = ?";
    $stmt_user = $conexion->prepare($sql_user);
    $stmt_user->bind_param("s", $usuario);
    $stmt_user->execute();
    $stmt_user->store_result();

    if ($stmt_user->num_rows > 0) {
        $stmt_user->close();
        $conexion->close();
        header("Location: administrar_usuarios.php?error=usuario");
        exit();
    }
    $stmt_user->close();

    $sql_mail = "SELECT co FROM usuarios WHERE co = ?";
    $stmt_mail = $conexion->prepare($sql_mail);
    $stmt_mail->bind_param("s", $correo);
    $stmt_mail->execute();
    $stmt_mail->store_result();

    if ($stmt_mail->num_rows > 0) {
        $stmt_mail->close();
        $conexion->close();
        header("Location: administrar_usuarios.php?error=correo");
        exit();
    }
    $stmt_mail->close();

    $password_encriptada = sha1($password);

    $sql_insert = "INSERT INTO usuarios (u, nombre, co, rol, c) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("sssss", $usuario, $nombre, $correo, $rol, $password_encriptada);

    if ($stmt_insert->execute()) {
        $stmt_insert->close();
        $conexion->close();
        header("Location: administrar_usuarios.php?success=1");
        exit();
    } else {
        $stmt_insert->close();
        $conexion->close();
        header("Location: administrar_usuarios.php?error=bd");
        exit();
    }

} else {
    header("Location: administrar_usuarios.php?error=acceso");
    exit();
}
?>