<?php
    session_start();
    unset ($SESSION['usuario']);
    session_destroy();

    header('location:login.php');

?>