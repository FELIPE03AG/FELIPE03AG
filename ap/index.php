<?php
session_start();
if(isset($_SESSION['nombre'])){
    header('location:principal.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GestAP</title>
    <link rel="icon" href="img/cerdo.ico" type="image/x-icon" />
    <script src="https://www.google.com/recaptcha/api.js?render=6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="font_awesome/css/all.min.css" rel="stylesheet" />
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles/style_index.css" />

    <script>
        function ejecutarRecaptcha() {
            grecaptcha.ready(function () {
                grecaptcha.execute('6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq', { action: 'homepage' }).then(function (token) {
                    // Token enviado al servidor si es necesario
                });
            });
        }
    </script>
</head>

<body onload="ejecutarRecaptcha()">
    <div class="login-container">
        <h1 class="logo-text">GestAP</h1>
        <form method="post" action="login.php">
            <!-- Usuario -->
            <div class="input-group mb-3">
                <span class="input-group-text">
                    <i class="fas fa-user"></i> <!-- Ícono de usuario -->
                </span>
                <input type="text" name="u" id="username" class="form-control" required placeholder="Usuario" />
            </div>

            <!-- Contraseña -->
            <div class="input-group mb-3">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i> <!-- Ícono de candado -->
                </span>
                <input type="password" name="c" id="password" class="form-control" required placeholder="Contraseña" />
            </div>

            <?php
            $valor = isset($_REQUEST['valor']) ? $_REQUEST['valor'] : NULL;
            if ($valor == 1) {
                echo '<div class="alert alert-danger d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span> Datos incorrectos, intente de nuevo.</span>
                      </div>';
            }
            if ($valor == 2) {
                echo '<div class="alert alert-danger d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-times-circle me-2"></i>
                        <span> Correo electrónico inválido.</span>
                      </div>';
            }
            if ($valor == 3) {
                echo '<div class="alert alert-success d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <span> ¡Contraseña cambiada exitosamente!</span>
                      </div>';
            }
            ?>

            <!-- Botón de inicio de sesión -->
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
    </div>
</body>

</html>