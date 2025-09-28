<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email no válido']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el email existe
    $stmt = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE co = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['success' => true, 'message' => 'Si el email existe, recibirás instrucciones para recuperar tu contraseña.']);
        exit;
    }

    // Generar token
    $token = bin2hex(random_bytes(32));
    $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Guardar token en BD
    $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacion = ?, token_expiracion = ? WHERE co = ?");
    $stmt->execute([$token, $expiracion, $email]);

    // Guardar en archivo de log para pruebas
    $logMessage = date('Y-m-d H:i:s') . " - Email: $email - Token: $token - Enlace: http://localhost/tu_proyecto/restablecer_clave.php?token=$token" . PHP_EOL;
    file_put_contents('tokens_recuperacion.log', $logMessage, FILE_APPEND);

    // Para desarrollo, mostrar el token directamente
    echo json_encode([
        'success' => true, 
        'message' => 'Para pruebas locales: ' . $token . ' - Revisa el archivo tokens_recuperacion.log'
    ]);

} catch (PDOException $e) {
    error_log("Error en recuperar_clave.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error del servidor. Intenta más tarde.']);
}
?>