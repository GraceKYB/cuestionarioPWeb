<?php

//variables
$servername ="localhost";
$username="root";
$password="";
$dbname= "cuestionario";
//creamos conexion
$conn = new mysqli($servername,$username,$password,$dbname);
//verificamos
if ($conn->connect_error){
die("Error de conexión".$conn->connect_error);
}
?>