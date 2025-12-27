<?php
$conexion = mysqli_connect("localhost", "root", "", "musica_db");

if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
?>
