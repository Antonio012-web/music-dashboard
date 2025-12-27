<?php include("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/conexion.php"); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $titulo = mysqli_real_escape_string($conexion, $_POST["titulo"]);

  $archivoNombre = $_FILES["archivo"]["name"];
  $archivoTmp = $_FILES["archivo"]["tmp_name"];
  $archivoRuta = "uploads/" . basename($archivoNombre);

  $portadaRuta = "assets/cover.jpg"; // Por defecto

  // Validar y mover portada (si se envió)
  if (!empty($_FILES["portada"]["name"])) {
    $portadaNombre = $_FILES["portada"]["name"];
    $portadaTmp = $_FILES["portada"]["tmp_name"];
    $portadaExt = strtolower(pathinfo($portadaNombre, PATHINFO_EXTENSION));
    $nombreSeguro = uniqid("portada_") . "." . $portadaExt;
    $portadaRuta = "assets/" . $nombreSeguro;

    $tamañoMax = 2 * 1024 * 1024; // 2MB

    // Validar tipo y tamaño
    if (
      in_array($portadaExt, ['jpg', 'jpeg', 'png']) &&
      $_FILES["portada"]["size"] <= $tamañoMax
    ) {
      if (!is_dir("assets")) {
        mkdir("assets", 0755, true);
      }
      if (!move_uploaded_file($portadaTmp, $portadaRuta)) {
        echo '<div class="alert alert-danger text-center">❌ Error al guardar la portada</div>';
        $portadaRuta = "assets/cover.jpg"; // Revertir a portada por defecto
      }
    } else {
      echo '<div class="alert alert-warning text-center">⚠️ Solo se permiten imágenes JPG, JPEG o PNG menores a 2MB.</div>';
      $portadaRuta = "assets/cover.jpg";
    }
  }

  // Guardar canción
  if (move_uploaded_file($archivoTmp, $archivoRuta)) {
    $query = "INSERT INTO canciones (titulo, archivo, portada) VALUES ('$titulo', '$archivoRuta', '$portadaRuta')";
    if (mysqli_query($conexion, $query)) {
      echo '<div class="alert alert-success text-center">🎉 ¡Canción subida exitosamente!</div>';
    } else {
      echo '<div class="alert alert-danger text-center">❌ Error al guardar en la base de datos</div>';
    }
  } else {
    echo '<div class="alert alert-danger text-center">❌ Error al subir el archivo de música</div>';
  }
}
?>

<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">📤 Subir Nueva Canción</h2>
    <form method="POST" enctype="multipart/form-data" class="col-md-6 mx-auto bg-light p-4 shadow rounded">
      <div class="mb-3">
        <label class="form-label">Título de la canción</label>
        <input type="text" name="titulo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Archivo MP3</label>
        <input type="file" name="archivo" class="form-control" accept=".mp3" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Portada (opcional, JPG/PNG, máx. 2MB)</label>
        <input type="file" name="portada" class="form-control" accept=".jpg,.jpeg,.png">
      </div>
      <button type="submit" class="btn btn-primary w-100">Subir Canción</button>
    </form>
  </div>
</section>

<?php include("includes/footer.php"); ?>
