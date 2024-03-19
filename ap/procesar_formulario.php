<?php
$secretKey = "TU_SECRET_KEY";
$response = $_POST['g-recaptcha-response'];

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$response}");
$responseData = json_decode($verify);

if ($responseData->success) {
    // El reCAPTCHA fue verificado con éxito
    // Procede a procesar el formulario
    // ...
} else {
    // El reCAPTCHA falló, maneja el error
    // ...
}
?>