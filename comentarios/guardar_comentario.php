<?php
session_start();
header("Content-Type: application/json");
include("../includes/conexion.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"]);
  $comentario = trim($_POST["comentario"]);
  $cancion_id = intval($_POST["cancion_id"]);

  $captcha = intval($_POST["captcha"] ?? -1);
$respuesta_correcta = $_SESSION["captcha_resultado"] ?? -999;

if ($captcha !== $respuesta_correcta) {
  echo json_encode(["success" => false, "error" => "❌ CAPTCHA incorrecto"]);
  exit();
}


  if ($nombre === "" || $comentario === "") {
    echo json_encode(["success" => false, "error" => "Campos vacíos"]);
    exit();
  }

  $nombre = mysqli_real_escape_string($conexion, $nombre);
  $comentario = mysqli_real_escape_string($conexion, $comentario);

  $insert = "INSERT INTO comentarios (cancion_id, nombre, comentario)
             VALUES ($cancion_id, '$nombre', '$comentario')";
  $ok = mysqli_query($conexion, $insert);

  if ($ok) {
    // Recargar comentarios de esa canción
    $res2 = mysqli_query($conexion, "SELECT * FROM comentarios WHERE cancion_id = $cancion_id ORDER BY fecha DESC");
    ob_start();
    while ($com = mysqli_fetch_assoc($res2)) {
      echo "<div class='mb-3'><strong>" . htmlspecialchars($com['nombre']) . "</strong> <br>" .
           nl2br(htmlspecialchars($com['comentario'])) . "<br><small class='text-muted'>" . $com['fecha'] . "</small></div>";
    }
    $comentariosHTML = ob_get_clean();

    echo json_encode(["success" => true, "comentarios_html" => $comentariosHTML]);
  } else {
    echo json_encode(["success" => false, "error" => "No se pudo guardar"]);
  }
}
