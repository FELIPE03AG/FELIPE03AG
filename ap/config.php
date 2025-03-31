<?php
ob_start();
//$host = "localhost";
//$db_username = "root";
//$db_password = "anayag";
//$db_name = "granja";
$conexion=new mysqli("localhost","root","JulioPP","granja");

//$link = mysqli_connect($host,$db_username,$db_password,$db_name) or
	//die ("Ha fallado la conexion".mysqli_connect_error());


ob_end_flush();  
?>