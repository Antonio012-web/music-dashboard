<?php 
session_start();
include("includes/header.php");
include("includes/conexion.php");

$id = intval($_GET["id"]);
$query = "SELECT * FROM canciones WHERE id = $id";
$res = mysqli_query($conexion, $query);
$cancion = mysqli_fetch_assoc($res);

if (!$cancion) {
  echo "<div class='alert alert-danger'>Canción no encontrada</div>";
  include("includes/footer.php"); exit();
}

// Likes por usuario
$resLikes = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM likes WHERE cancion_id = $id");
$totalLikes = mysqli_fetch_assoc($resLikes)["total"];

$usuario_id = $_SESSION["usuario_id"] ?? 0;
$ya_dio_like = false;
if ($usuario_id) {
  $resLike = mysqli_query($conexion, "SELECT * FROM likes WHERE cancion_id = $id AND usuario_id = $usuario_id");
  $ya_dio_like = mysqli_num_rows($resLike) > 0;
}

// CAPTCHA para comentarios
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$_SESSION["captcha_resultado"] = $num1 + $num2;
?>

<div class="main-content pt-5">
  <div class="bg-dark text-white rounded shadow p-4">
    <div class="d-flex align-items-center">
      <img src="<?= $cancion['portada'] ?>" class="rounded me-4" alt="portada" style="width: 100px; height: 100px; object-fit: cover;">
      <div>
        <h3 class="mb-1"><?= htmlspecialchars($cancion['titulo']) ?></h3>
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-light btn-sm me-3" onclick="cargarCancion('<?= htmlspecialchars($cancion['titulo']) ?>', '<?= $cancion['archivo'] ?>', '<?= $cancion['portada'] ?>')">
            ▶️ Reproducir aquí
          </button>
          <?php if ($usuario_id): ?>
            <button id="btn-like" class="btn <?= $ya_dio_like ? 'btn-danger' : 'btn-outline-danger' ?> btn-sm">
              ❤️ <span id="like-text"><?= $ya_dio_like ? 'Quitar like' : 'Me gusta' ?></span> 
              (<span id="like-count"><?= $totalLikes ?></span>)
            </button>
          <?php else: ?>
            <div class="text-warning">Inicia sesión para dar like ❤️</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-5">
    <h4 class="text-white mb-3">💬 Comentarios</h4>
    <form id="form-comentario" class="bg-dark p-4 rounded shadow">
      <input type="hidden" name="cancion_id" value="<?= $cancion['id'] ?>">
      <div class="mb-3">
        <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required>
      </div>
      <div class="mb-3">
        <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe un comentario..." required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">¿Cuánto es <?= $num1 ?> + <?= $num2 ?>?</label>
        <input type="number" name="captcha" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Enviar</button>
      <div id="mensaje-comentario" class="mt-3"></div>
    </form>

    <hr class="bg-secondary my-4">

    <div id="comentarios-lista">
      <?php
        $res2 = mysqli_query($conexion, "SELECT * FROM comentarios WHERE cancion_id = $id ORDER BY fecha DESC");
        while ($com = mysqli_fetch_assoc($res2)) {
          echo "<div class='bg-dark text-white p-3 rounded mb-3 shadow-sm'>
                  <strong>" . htmlspecialchars($com['nombre']) . "</strong><br>
                  " . nl2br(htmlspecialchars($com['comentario'])) . "<br>
                  <small class='text-muted'>" . $com['fecha'] . "</small>
                </div>";
        }
      ?>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
// Enviar comentario vía AJAX
const formComentario = document.getElementById("form-comentario");
formComentario.addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  const mensajeDiv = document.getElementById("mensaje-comentario");

  fetch("comentarios/guardar_comentario.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      mensajeDiv.innerHTML = '<div class="alert alert-success">✅ Comentario enviado</div>';
      this.reset();
      document.getElementById("comentarios-lista").innerHTML = data.comentarios_html;
    } else {
      mensajeDiv.innerHTML = '<div class="alert alert-danger">❌ ' + data.error + '</div>';
    }
  })
  .catch(() => {
    mensajeDiv.innerHTML = '<div class="alert alert-danger">❌ Error en el servidor</div>';
  });
});
</script>

<?php if ($usuario_id): ?>
<script>
// Like / Quitar like vía AJAX
const btnLike = document.getElementById("btn-like");
btnLike?.addEventListener("click", function () {
  fetch("likes/like.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ cancion_id: <?= $id ?> })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById("like-count").textContent = data.totalLikes;
      const txt = document.getElementById("like-text");
      if (data.estado === "added") {
        btnLike.classList.remove("btn-outline-danger");
        btnLike.classList.add("btn-danger");
        txt.textContent = "Quitar like";
      } else {
        btnLike.classList.remove("btn-danger");
        btnLike.classList.add("btn-outline-danger");
        txt.textContent = "Me gusta";
      }
    }
  });
});
</script>
<?php endif; ?>
