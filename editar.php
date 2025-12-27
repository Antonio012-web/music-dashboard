<?php include("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/conexion.php");

$id = intval($_GET['id']);
$query = "SELECT * FROM canciones WHERE id = $id LIMIT 1";
$result = mysqli_query($conexion, $query);
$cancion = mysqli_fetch_assoc($result);

if (!$cancion) {
  echo "<div class='alert alert-danger'>Canción no encontrada</div>";
  include("includes/footer.php");
  exit();
}

// Procesar edición
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $titulo = mysqli_real_escape_string($conexion, $_POST["titulo"]);
  $archivoRuta = $cancion['archivo'];
  $portadaRuta = $cancion['portada'];

  // Si se sube nuevo MP3
  if (!empty($_FILES["archivo"]["name"])) {
    if (file_exists($archivoRuta)) unlink($archivoRuta);
    $archivoNombre = $_FILES["archivo"]["name"];
    $archivoTmp = $_FILES["archivo"]["tmp_name"];
    $archivoRuta = "uploads/" . basename($archivoNombre);
    move_uploaded_file($archivoTmp, $archivoRuta);
  }

  // Si se sube nueva portada
  if (!empty($_FILES["portada"]["name"])) {
    if ($portadaRuta !== 'assets/cover.jpg' && file_exists($portadaRuta)) unlink($portadaRuta);
    $ext = pathinfo($_FILES["portada"]["name"], PATHINFO_EXTENSION);
    $nombreSeguro = uniqid("portada_") . "." . $ext;
    $portadaTmp = $_FILES["portada"]["tmp_name"];
    $portadaRuta = "assets/" . $nombreSeguro;
    move_uploaded_file($portadaTmp, $portadaRuta);
  }

  // Actualizar BD
  $update = "UPDATE canciones SET titulo = '$titulo', archivo = '$archivoRuta', portada = '$portadaRuta' WHERE id = $id";
  mysqli_query($conexion, $update);
  header("Location: panel.php");
  exit();
}
?>

<div class="container py-5">
  <div class="col-md-6 mx-auto bg-light p-4 rounded shadow">
    <h4 class="text-center mb-4">✏️ Editar Canción</h4>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Título</label>
        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($cancion['titulo']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Archivo MP3 (deja vacío para no cambiar)</label>
        <input type="file" name="archivo" class="form-control" accept=".mp3">
      </div>
      <div class="mb-3">
        <label>Portada (opcional)</label>
        <input type="file" name="portada" class="form-control" accept=".jpg,.jpeg,.png">
      </div>
      <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
    </form>
  </div>
</div>

<?php include("includes/footer.php"); ?>
