<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION PORCINA AP</title>
    <script src="https://www.google.com/recaptcha/api.js?render=6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq"></script>

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font_awesome/css/all.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>

    <script>
        function ejecutarRecaptcha() {
            grecaptcha.ready(function() {
                // Realiza la llamada para obtener la puntuación del usuario
                grecaptcha.execute('6LeDJZ4pAAAAAO4I7EBvtsTm5qEm2p85sJYFwjqq', {action: 'homepage'}).then(function(token) {
                    // Envía el token al servidor para su procesamiento
                    // Por ejemplo, puedes enviarlo con una solicitud AJAX
                });
            });
        }
    </script>
</head>
<body onload="ejecutarRecaptcha()">

  <style>
   body {
            background-image: url('img/inifoto.png');
            background-size: cover; /* para cubrir todo el fondo */
            background-position: center; /* para centrar la imagen */
            /* Añade más estilos si es necesario */
        }
  </style>

      
  <div class="container text-center">
    <div class="row">
      <div class="col">
        <div>
          <br>
          <br>
          <br>
        </div>
        <img src="logotipo.png" width="500px"/>
    </div>
    
    <div class="col">
        <div>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
        </div>
        <form method="post" action="login.php">
        
        <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="username" name="u" id="form2Example1" class="form-control" />
            <label class="form-label" for="form2Example1" style="color: white;">Usuario</label>
          </div>
        
          <!-- Password input -->
          <div class="form-outline mb-4">
            <input type="password" name="c" id="form2Example2" class="form-control" />
            <label class="form-label" for="form2Example2"style="color: white;">Contraseña</label>
          </div>

            <?php
            ob_start();
            $valor = isset($_REQUEST['valor']) ? $_REQUEST['valor']: NULL;
            if($valor == 1)
            {
              echo'<div class="alert alert-danger" role="alert" style="text-align: center;">
             ***Datos Incorrectos***
              </div>';

            }
            ob_end_flush();  
            ?>
          
        
          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">
              <!-- Checkbox -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                <label class="form-check-label" for="form2Example31"style="color: white;"> Recuerdame </label>
              </div>
            </div>
        
            <div class="col">
              <!-- Simple link -->
              <a href="recuperar_clave.php"style="color: white;">Olvido la contraseña?</a>
            </div>
          </div>
        
          <!-- Submit button -->
          <button type="submit" class="btn btn-primary btn-block mb-4"style="color: white;">Iniciar sesión</button>
        
          <!-- Register button -->
          <div class="text-center">
            <p> <label style ="color: white"> No tiene cuenta? <a href="register.php"style="color: white;">Registrarme</a> </label></p>
            
          </div>
        </form>


      </div>

</body>
<script src="js/snippets.js"></script>
<script src="js/modals.js"></script>
</html>