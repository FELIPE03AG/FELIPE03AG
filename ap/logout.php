<?php
ob_start();
    session_start();
    unset ($SESSION['usuario']);
    session_destroy();

    header('location:login.php');
    ob_end_flush();  
?>