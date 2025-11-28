<?php
session_start();
if(isset($_SESSION['nombre'])){
    header('location:cerdos.php');
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
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

        // Mostrar modal personalizado para recuperar contraseña
        $(document).ready(function () {
            // Abrir modal al dar clic en el enlace
            $(document).on('click', '#recuperarLink', function (e) {
                e.preventDefault();
                $('#recuperarModal').modal('show');
            });

            // Antes de enviar el formulario, generar token reCAPTCHA y añadirlo al form
            $('#recuperarForm').on('submit', function (e) {
                e.preventDefault();
                var $form = $(this);
                grecaptcha.ready(function () {
                    grecaptcha.execute('6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq', { action: 'recuperar' }).then(function (token) {
                        // Añadir token al formulario
                        if ($form.find('input[name="g-recaptcha-response"]').length === 0) {
                            $form.append('<input type="hidden" name="g-recaptcha-response" />');
                        }
                        $form.find('input[name="g-recaptcha-response"]').val(token);

                        // Finalmente enviar el formulario al servidor (submit normal)
                        $form.off('submit'); // evitar loop
                        $form.trigger('submit');
                    });
                });
            });

            // FALLBACK: cerrar modal manualmente si los data-attributes no funcionaran
            // (esto captura el click en la 'x' o en cualquier elemento con data-dismiss="modal")
            $(document).on('click', '#recuperarModal .close, #recuperarModal [data-dismiss="modal"]', function () {
                $('#recuperarModal').modal('hide');
            });
        });
    </script>
</head>

<body onload="ejecutarRecaptcha()">
    <div class="login-container">
        <img src="img/Logo1.png" alt="GestAP" class="logo-img img-fluid">
        <form method="post" action="login.php">    
            <!-- Usuario -->
            <div class="input-group mb-3 shadow-sm">
                <span class="input-group-text bg-white">
                    <i class="fas fa-user text-primary"></i>
                </span>

                <input type="text" 
                    name="u" 
                    id="username" 
                    class="form-control border-start-0 ps-3" 
                    required 
                    placeholder="Usuario" />
            </div>

            <!-- Contraseña -->
            <div class="input-group mb-3 shadow-sm">
                <span class="input-group-text bg-white">
                    <i class="fas fa-lock text-primary"></i>
                </span>

                <input type="password" 
                    name="c" 
                    id="password" 
                    class="form-control border-start-0 border-end-0 ps-3" 
                    required 
                    placeholder="Contraseña" />

                <!-- Botón para mostrar/ocultar -->
                <button type="button" 
                        class="btn bg-white border" 
                        onclick="togglePassword()"
                        style="border-left:0;">
                    <i class="fas fa-eye text-secondary" id="eyeIcon"></i>
                </button>
            </div>

            <?php
            $valor = isset($_REQUEST['valor']) ? $_REQUEST['valor'] : NULL;

            if ($valor == 1) {
                echo '<div class="alert alert-danger d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span>Datos incorrectos, intente de nuevo.</span>
                    </div>';
            }
            if ($valor == 2) {
                echo '<div class="alert alert-danger d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-times-circle me-2"></i>
                        <span>Correo electrónico inválido.</span>
                    </div>';
            }
            if ($valor == 3) {
                echo '<div class="alert alert-success d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>¡Contraseña cambiada exitosamente!</span>
                    </div>';
            }
            if ($valor == 4) {
                echo '<div class="alert alert-warning d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-user-slash me-2"></i>
                        <span>El usuario ingresado no existe.</span>
                    </div>';
            }
            if ($valor == 5) {
                echo '<div class="alert alert-danger d-flex align-items-center fade show p-2 rounded" role="alert">
                        <i class="fas fa-lock me-2"></i>
                        <span>La contraseña ingresada es incorrecta.</span>
                    </div>';
            }
            ?>


            <!-- Botón de inicio de sesión -->
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
    </div>

    <script>
    function togglePassword() {
        const input = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
    </script>

</body>
</html>