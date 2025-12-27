<?php
session_start();
include("includes/conexion.php");
include("includes/header.php");

$usuario_id = $_SESSION["usuario_id"] ?? 0;
$res = mysqli_query($conexion, "SELECT * FROM canciones ORDER BY id DESC");
?>

<!-- Banner del artista -->
<div class="artist-header text-white" style="
  background-image: url('assets/fondo_artistico.jpg');
  background-size: cover;
  background-position: center;
  padding: 60px 30px 100px;
  border-bottom: 2px solid #222;
  position: relative;
">
  <div class="d-flex align-items-center">
    <img src="assets/avatar.png" class="rounded-circle shadow me-4" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid white;">
    <div>
      <h2 class="mb-1">Epicenter Nation</h2>
      <span class="badge bg-primary me-2">México</span>
      <span class="badge bg-warning text-dark">Electrónica</span>
    </div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs mt-4" style="border: none;">
    <li class="nav-item">
      <a class="nav-link active text-white bg-dark border-0" href="#">Publicaciones</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="#">Álbumes</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="#">Acerca de</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="#">Suscripciones</a>
    </li>
  </ul>
</div>

<!-- Lista de canciones -->
<div class="main-content pt-4">
  <h4 class="mb-4">Lista de canciones</h4>

  <ul class="list-group list-group-flush">
    <?php while ($c = mysqli_fetch_assoc($res)): ?>
      <?php
        $ya_dio_like = false;
        $totalLikes = 0;
        if ($usuario_id) {
          $r1 = mysqli_query($conexion, "SELECT 1 FROM likes WHERE cancion_id = {$c['id']} AND usuario_id = $usuario_id");
          $ya_dio_like = mysqli_num_rows($r1) > 0;
        }
        $r2 = mysqli_query($conexion, "SELECT COUNT(*) as total FROM likes WHERE cancion_id = {$c['id']}");
        $totalLikes = mysqli_fetch_assoc($r2)["total"];
      ?>
      <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white border-secondary">
        <div class="d-flex align-items-center">
          <img src="<?= $c['portada'] ?>" alt="Portada" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 15px;">
          <div>
            <div class="fw-bold"><?= htmlspecialchars($c['titulo']) ?></div>
            <small class="text-muted"><?= pathinfo($c['archivo'], PATHINFO_FILENAME) ?>.mp3</small>
          </div>
        </div>

        <div class="d-flex align-items-center">
          <button class="btn btn-outline-light btn-sm me-2"
                  onclick="cargarCancion('<?= htmlspecialchars($c['titulo']) ?>', '<?= $c['archivo'] ?>', '<?= $c['portada'] ?>')">
            ▶️ Escuchar
          </button>
          <?php if ($usuario_id): ?>
            <button class="btn <?= $ya_dio_like ? 'btn-danger' : 'btn-outline-danger' ?> btn-sm btn-like" 
                    data-id="<?= $c['id'] ?>">
              ❤️ <span class="like-text"><?= $ya_dio_like ? 'Quitar' : 'Like' ?></span> 
              (<span class="like-count"><?= $totalLikes ?></span>)
            </button>
          <?php endif; ?>
        </div>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

<?php include("includes/footer.php"); ?>

<script>
document.querySelectorAll(".btn-like").forEach(btn => {
  btn.addEventListener("click", function () {
    const cancionId = this.dataset.id;
    const button = this;

    fetch("likes/like.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ cancion_id: cancionId })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        button.querySelector(".like-count").textContent = data.totalLikes;
        const text = button.querySelector(".like-text");
        text.textContent = data.estado === "added" ? "Quitar" : "Like";
        button.classList.toggle("btn-outline-danger");
        button.classList.toggle("btn-danger");
      }
    });
  });
});
</script>
