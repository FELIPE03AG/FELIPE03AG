<?php

session_start();
if(isset($_SESSION['nombre'])){
    header('location:principal.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GestAP</title>
    
    <script src="https://www.google.com/recaptcha/api.js?render=6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq"></script>
    <link rel="icon" href="img/cerdo.png" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles2.css">

    <style>
        body {
            background-image: url('img/fondo1.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        .form-label {
            color: #333 !important;
        }

        .btn-primary {
            width: 100%;
        }
    </style>

    <script>
        function ejecutarRecaptcha() {
            grecaptcha.ready(function() {
                grecaptcha.execute('6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq', {action: 'homepage'}).then(function(token) {
                    // Token enviado al servidor si es necesario
                });
            });
        }
    </script>
</head>
<body onload="ejecutarRecaptcha()">

    <div class="login-container">
        <h1 class ="logo-text">GestAP</h1>
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

    <!-- Modal de Recuperación de Contraseña -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="recon.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="correo" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>