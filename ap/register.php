<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESTAURAR SU CLAVE DE ACCESO</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <Link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
  
<style>
   body {
            background-image: url('img/fnd.jpeg');
            background-size: cover; /* para cubrir todo el fondo */
            background-position: center; /* para centrar la imagen */
            /* Añade más estilos si es necesario */
        }
  </style>
  
      <button><a href="index.php">Regresar al Lobby</a></button>

      <div class="container text-center">
        <div class="row">
          <div class="col">
            <br>
            
            
            <img src="logotipo.png" width="200px" alt="Descripción de mi SVG" />
          </div>
  
    
    
    
        <div class="col">
           <br>
           <br>
            
            <form class="row g-3">
                <div class="col-md-4">
                  <label for="validationServer01" class="form-label">Nombre(s)</label>
                  <input type="text" class="form-control is-valid" id="validationServer01" value="" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="validationServer02" class="form-label"style="color: white;">Apellidos</label>
                  <input type="text" class="form-control is-valid" id="validationServer02" value="" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="validationServerUsername" class="form-label"style="color: white;">Correo electronico</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend3">@</span>
                    <input type="text" class="form-control is-invalid" id="validationServerUsername" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                      Please choose a username.
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <label for="validationServer01" class="form-label">Numero de Telefono</label>
                  <input type="text" class="form-control is-valid" id="validationServer01" value="" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>
                
                <div class="col-md-4">
                  <label for="validationServer01" class="form-label">Clave de Acceso</label>
                  <input type="text" class="form-control is-valid" id="validationServer01" value="" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>

                <div class="col-md-4">
                  <label for="validationServer01" class="form-label">Confirmar Clave</label>
                  <input type="text" class="form-control is-valid" id="validationServer01" value="Name" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input is-invalid" type="checkbox" value="" id="invalidCheck3" aria-describedby="invalidCheck3Feedback" required>
                    <label class="form-check-label" for="invalidCheck3">
                      Agree to terms and conditions
                    </label>
                    <div id="invalidCheck3Feedback" class="invalid-feedback">
                      You must agree before submitting.
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <button class="btn btn-primary" type="submit">Submit form</button>
                </div>
              </form>
              
    
              
              
      
            </div>
            

        
    
          </div>
    
</body>
</html>