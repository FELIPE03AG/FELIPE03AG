<?php
session_start();
// Incluir archivo de conexión a la base de datos
include("config.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre de usuario del formulario
    $usuario_a_eliminar = trim($_POST['u']);

    // 1. Validar que el nombre de usuario no esté vacío
    if (empty($usuario_a_eliminar)) {
        $_SESSION['mensaje_error'] = "El nombre de usuario es obligatorio.";
        header('location: administrar_usuarios.php');
        exit();
    }
    
    // --- NUEVO: 2. VERIFICAR EL ROL DEL USUARIO ANTES DE ELIMINAR ---
    $sql_check_role = "SELECT rol FROM usuarios WHERE u = ?";

    if ($stmt_check = $conexion->prepare($sql_check_role)) {
        $stmt_check->bind_param("s", $usuario_a_eliminar);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            $rol_a_eliminar = $row['rol'];
            $stmt_check->close();

            // Aplicar la restricción: Si el rol es 'admin', se aborta la eliminación
            if ($rol_a_eliminar === 'admin') {
                // Mensaje específico para el modal
                $_SESSION['modal_error_title'] = "Operación Denegada";
                $_SESSION['modal_error_body'] = "No es posible eliminar al usuario '{$usuario_a_eliminar}' porque tiene el rol de Administrador. Esta es una medida de seguridad.";
                $_SESSION['show_modal'] = true; // Flag para activar el modal en el frontend
                
                $conexion->close();
                header('location: administrar_usuarios.php');
                exit(); 
            }
        } else {
            // Si el usuario no existe
            $stmt_check->close();
            $_SESSION['mensaje_error'] = "Error: El usuario '{$usuario_a_eliminar}' no fue encontrado en la base de datos.";
            $conexion->close();
            header('location: administrar_usuarios.php');
            exit();
        }

    } else {
        $_SESSION['mensaje_error'] = "Error al preparar la consulta de verificación de rol.";
        $conexion->close();
        header('location: administrar_usuarios.php');
        exit();
    }
    // ----------------------------------------------------------------


    // 3. Preparar la consulta SQL para eliminar al usuario (solo si el rol no era 'admin')
    $sql_delete = "DELETE FROM usuarios WHERE u = ?";

    // Usar prepared statements para evitar inyecciones SQL
    if ($stmt_delete = $conexion->prepare($sql_delete)) {
        // Vincular el parámetro
        $stmt_delete->bind_param("s", $usuario_a_eliminar);

        // Ejecutar la consulta
        if ($stmt_delete->execute()) {
            $_SESSION['mensaje_exito'] = "Usuario '{$usuario_a_eliminar}' eliminado correctamente.";
        } else {
            $_SESSION['mensaje_error'] = "Error al eliminar el usuario: " . $stmt_delete->error;
        }

        // Cerrar el statement
        $stmt_delete->close();
    } else {
        $_SESSION['mensaje_error'] = "Error al preparar la consulta SQL de eliminación.";
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    // Redirigir siempre al final
    header('location: administrar_usuarios.php');
    exit();
} else {
    // Si no es un método POST, redirigir
    header('location: administrar_usuarios.php');
    exit();
}
?>