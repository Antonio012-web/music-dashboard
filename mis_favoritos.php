<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.php");
  exit();
}

include("includes/conexion.php");
include("includes/header.php");

$usuario_id = $_SESSION["usuario_id"];

$query = "
  SELECT c.*
  FROM canciones c
  INNER JOIN likes l ON c.id = l.cancion_id
  WHERE l.usuario_id = ?
  ORDER BY l.fecha DESC
";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<div class="container py-5">
  <h3 class="mb-4">❤️ Mis Canciones Favoritas</h3>

  <div id="favoritos-lista">
  <?php if ($res->num_rows === 0): ?>
    <div class="alert alert-info">Aún no has dado like a ninguna canción.</div>
  <?php else: ?>
    <div class="row">
      <?php while ($c = $res->fetch_assoc()): ?>
        <div class="col-md-4 mb-4 favorito-item" id="fav-<?= $c['id'] ?>">
          <div class="card h-100 shadow-sm">
            <img src="<?= $c['portada'] ?>" class="card-img-top" alt="Portada">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($c['titulo']) ?></h5>
              <a href="reproducir.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-sm">Escuchar</a>
              <button class="btn btn-outline-danger btn-sm quitar-like" data-id="<?= $c['id'] ?>">Quitar ❤️</button>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
  </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
document.querySelectorAll(".quitar-like").forEach(btn => {
  btn.addEventListener("click", function () {
    const cancionId = this.dataset.id;

    fetch("likes/like.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ cancion_id: cancionId })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.estado === "removed") {
        // Quita el elemento del DOM
        document.getElementById("fav-" + cancionId).remove();

        // Si ya no hay favoritos, mostrar mensaje
        if (document.querySelectorAll(".favorito-item").length === 0) {
          document.getElementById("favoritos-lista").innerHTML = `
            <div class="alert alert-info">Aún no has dado like a ninguna canción.</div>
          `;
        }
      }
    });
  });
});
</script>
