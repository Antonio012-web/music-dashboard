<?php include("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/conexion.php"); ?>

<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">🎚 Panel de Administración</h2>
    <a href="subir.php" class="btn btn-success mb-3">➕ Nueva Canción</a>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Portada</th>
          <th>Título</th>
          <th>Archivo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT * FROM canciones ORDER BY id DESC";
        $resultado = mysqli_query($conexion, $query);
        while ($row = mysqli_fetch_assoc($resultado)) {
        ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><img src="<?= $row['portada'] ?>" width="60" height="60" style="object-fit:cover;"></td>
            <td><?= htmlspecialchars($row['titulo']) ?></td>
            <td><small><?= $row['archivo'] ?></small></td>
            <td>
              <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
              <a href="eliminar.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta canción?')">Eliminar</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</section>

<?php include("includes/footer.php"); ?>
