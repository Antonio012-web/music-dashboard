<?php
include("includes/auth.php");
include("includes/conexion.php");

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $query = "SELECT * FROM canciones WHERE id = $id LIMIT 1";
  $result = mysqli_query($conexion, $query);
  $cancion = mysqli_fetch_assoc($result);

  if ($cancion) {
    // Eliminar archivos
    if (file_exists($cancion['archivo'])) unlink($cancion['archivo']);
    if ($cancion['portada'] !== 'assets/cover.jpg' && file_exists($cancion['portada'])) unlink($cancion['portada']);

    // Eliminar de base de datos
    $delete = "DELETE FROM canciones WHERE id = $id";
    mysqli_query($conexion, $delete);
  }
}

header("Location: panel.php");
exit();
