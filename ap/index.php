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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS y jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    

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
            background-image: url('img/fondo3.png');
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
        <img src="logotipo.png" width="300px"/>
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
            $codigo = isset($_REQUEST['cod']) ? $_REQUEST['cod']: NULL;
            include("config.php");

            $showModal = false;

            
           
            if($valor == 1)
            {
              echo'<div class="alert alert-danger" role="alert" style="text-align: center;">
             ***Datos Incorrectos***
              </div>';

            }

            if($valor == 2)
            {
              echo'<div class="alert alert-danger" role="alert" style="text-align: center;">
             ***Correo invalido***
              </div>';

            }
            if($valor == 3)
            {
              echo'<div class="alert alert-success" role="alert" style="text-align: center;">
             Contraseña Cambiada!
              </div>';

            }

            if($codigo != NULL)
            {

              $consulta = mysqli_query($conexion, "SELECT * FROM solcon WHERE codigo = '$codigo'");
                while ($fila=mysqli_fetch_array($consulta))
                            {
                                $idu=$fila["idu"];
                            }

                if($idu==null){echo"error";}
                else{
                 //echo $idu;
                 $showModal = true;

                 
                 
                  

                echo'<p>  </P>';
                

                  
                  
                  // aparecera el modal o pagina para cambiar la, contrase;a enviar a otro archivo que cambie la contrase;a 
                  //segun el id u
                }
                
                            
             
                
             

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
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Olvide mi Contraseña
              </button>

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



 <!-- Botón oculto para abrir el modal -->
 <button id="openModalButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#autoOpenModal" style="display: none;">
        Abrir Modal
    </button>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="autoOpenModal" tabindex="-1" aria-labelledby="autoOpenModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="autoOpenModalLabel">Cambio de Contraseña</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body">

                  <form id="changePasswordForm" action="restaurar.php" method="POST">
                    <div class="form-group">
                    <label for="newPassword">Nueva Contraseña</label>
                    <input type="password" class="form-control" name="newPassword" id="newPassword" required>
                    </div>
                    <div class="form-group">
                    <label for="confirmPassword">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required>
                    <form action="otro_archivo.php" method="post">
                    <input type="hidden" name="idu" value="<?php echo htmlspecialchars($idu, ENT_QUOTES, 'UTF-8'); ?>">
                    
                  
          
     
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                  </form>
                  <script>
document.getElementById("changePasswordForm").addEventListener("submit", function(event) {
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (newPassword !== confirmPassword) {
    event.preventDefault(); // Evita que el formulario se envíe
    alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo."); // Muestra un mensaje de error
  }
});
</script>


              


              
              
              </div>
              <div class="modal-footer">
                  
              </div>
          </div>
      </div>
  </div>



  <!-- Código para abrir el modal automáticamente -->
  <script>
  $(document).ready(function() {
      var showModal = <?php echo json_encode($showModal); ?>; // Transforma el valor PHP en JavaScript

      if (showModal) {
          $('#openModalButton').click(); // Simula el clic para abrir el modal
      }
  });
  </script>


     

</body>
<!-- Modal 1 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Recuperar Contraseña</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
       
       
        <form method="post" action="recon.php" id="formul">
            <div class="form-outline mb-4">
              <input type="email" id="form2Examplell" name="correo" class="form-control"
              placeholder="correo electronico"/>
              <label class="form-label" for="form2Examplell">Correo electronico</label>
            </div>



            

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <input type="submit" form="formul" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>





 




<script src="js/snippets.js"></script>
<script src="js/modals.js"></script>

</html>