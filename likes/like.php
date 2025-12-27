<?php
session_start();
include("../includes/conexion.php");
header("Content-Type: application/json");

if (!isset($_SESSION["usuario_id"])) {
  echo json_encode(["success" => false, "message" => "Debes iniciar sesión para dar like"]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$cancion_id = intval($data["cancion_id"] ?? 0);
$usuario_id = intval($_SESSION["usuario_id"]);

if ($cancion_id <= 0 || $usuario_id <= 0) {
  echo json_encode(["success" => false, "message" => "Datos inválidos"]);
  exit();
}

// ¿Ya dio like?
$check = mysqli_query($conexion, "SELECT * FROM likes WHERE cancion_id = $cancion_id AND usuario_id = $usuario_id");
if (mysqli_num_rows($check) > 0) {
  // Quitar like
  mysqli_query($conexion, "DELETE FROM likes WHERE cancion_id = $cancion_id AND usuario_id = $usuario_id");
  $estado = "removed";
} else {
  // Agregar like
  mysqli_query($conexion, "INSERT INTO likes (cancion_id, usuario_id) VALUES ($cancion_id, $usuario_id)");
  $estado = "added";
}

// Obtener total actualizado
$res = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM likes WHERE cancion_id = $cancion_id");
$totalLikes = mysqli_fetch_assoc($res)["total"];

echo json_encode([
  "success" => true,
  "estado" => $estado,
  "totalLikes" => $totalLikes
]);
