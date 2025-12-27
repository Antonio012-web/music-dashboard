<?php include("includes/header.php"); ?>
<?php include("includes/conexion.php"); ?>

<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">🎵 Catálogo de Canciones</h2>
    <div class="row">

    <?php
      $query = "SELECT * FROM canciones ORDER BY id DESC";
      $resultado = mysqli_query($conexion, $query);
      while ($cancion = mysqli_fetch_assoc($resultado)) 
      {
    ?>
    <?php
$resLikes = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM likes WHERE cancion_id = {$cancion['id']}");
$likes = mysqli_fetch_assoc($resLikes)['total'];
?>

      <div class="col-md-4 mb-4">
        <div class="card shadow">
          <img src="<?= $cancion['portada'] ?>" class="card-img-top" alt="Portada">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($cancion['titulo']) ?></h5>
            <p class="text-muted">❤️ <?= $likes ?> </p>

            <audio controls class="w-100">
              <source src="<?= htmlspecialchars($cancion['archivo']) ?>" type="audio/mpeg">
              Tu navegador no soporta audio HTML5.
            </audio>
            <div class="mt-3 d-flex justify-content-between">
              <a href="<?= htmlspecialchars($cancion['archivo']) ?>" download class="btn btn-sm btn-outline-success">Descargar</a>
              <a href="reproducir.php?id=<?= $cancion['id'] ?>" class="btn btn-sm btn-outline-primary">Ver / Comentar</a>
              <button class="btn btn-sm btn-outline-secondary" onclick="compartirCancion('<?= htmlspecialchars($cancion['archivo']) ?>')">Compartir</button>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    </div>
  </div>
</section>

<script>
function compartirCancion(url) {
  const enlace = location.origin + '/' + url;
  navigator.clipboard.writeText(enlace);
  alert("¡Enlace copiado al portapapeles!");
}
</script>

<?php include("includes/footer.php"); ?>
