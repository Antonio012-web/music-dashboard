<footer class="bg-dark text-white text-center py-4 mt-5">
  <div class="container">
    <p class="mb-0">© <?php echo date("Y"); ?> Epicenter Nation. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        const countSpan = button.querySelector(".like-count");
        const label = button.querySelector("span");

        countSpan.textContent = data.totalLikes;
        button.classList.toggle("btn-outline-danger");
        button.classList.toggle("btn-danger");
        label.textContent = data.estado === "added" ? "Quitar" : "Like";
      }
    });
  });
});
</script>
<!-- Reproductor inferior fijo -->
<div id="reproductor-global" class="fixed-bottom bg-dark text-white p-2 d-flex align-items-center shadow" style="display: none;">
  <img id="reproductor-portada" src="" alt="portada" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
  <div class="flex-grow-1">
    <div id="reproductor-titulo" class="fw-bold">Título de la canción</div>
    <input id="reproductor-progreso" type="range" class="form-range" value="0" min="0" step="1">
  </div>
  <button id="reproductor-play" class="btn btn-light ms-2 me-2"><i class="bi bi-play-fill"></i></button>
  <input id="reproductor-volumen" type="range" class="form-range ms-2" style="width: 100px;" min="0" max="1" step="0.01" value="1">
  <audio id="audio-global" preload="metadata"></audio>
</div>

<!-- Bootstrap JS y tu script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/reproductor.js"></script>
</body>
</html>

</body>
</html>
