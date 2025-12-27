<!-- includes/sidebar.php -->
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="sidebar">
  <a href="index.php" class="d-flex align-items-center mb-4 text-white text-decoration-none">
    <img src="assets/logo.png" alt="Logo" style="height: 32px; margin-right: 10px;">
    <span class="fs-4">Epicenter</span>
  </a>

  <?php if (isset($_SESSION["usuario"])): ?>
    <div class="mb-3"> 
      <small class="text-white-50">Bienvenido</small><br>
      <strong><?= htmlspecialchars($_SESSION["usuario"]) ?></strong>
    </div>
  <?php endif; ?>

  <ul class="nav nav-pills flex-column mb-auto">
    <li><a href="#" class="nav-link text-white"><i class="bi bi-person-circle me-2"></i> My Creators</a></li>
    <li><a href="#" class="nav-link text-white"><i class="bi bi-star me-2"></i> Fanzone</a></li>
    
    <li class="mt-3 text-muted">Library</li>
    <li><a href="tracks.php" class="nav-link text-white"><i class="bi bi-music-note-list me-2"></i> Tracks</a></li>
    <li><a href="artists.php" class="nav-link text-white"><i class="bi bi-person-bounding-box me-2"></i> Artists</a></li>
    <li><a href="podcasts.php" class="nav-link text-white"><i class="bi bi-mic me-2"></i> Podcasts</a></li>
    <li><a href="radios.php" class="nav-link text-white"><i class="bi bi-broadcast me-2"></i> Radio Stations</a></li>

    <?php if (isset($_SESSION["usuario_id"])): ?>
      <li class="mt-3 text-muted">Opciones</li>
      <li><a href="subir.php" class="nav-link text-white"><i class="bi bi-cloud-arrow-up me-2"></i> Subir canción</a></li>
      <li><a href="mis_favoritos.php" class="nav-link text-white"><i class="bi bi-heart me-2"></i> Mis favoritos</a></li>
      <li><a href="logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a></li>
    <?php else: ?>
      <li class="mt-3 text-muted">Cuenta</li>
      <li><a href="login.php" class="nav-link text-white"><i class="bi bi-box-arrow-in-right me-2"></i> Iniciar sesión</a></li>
      <li><a href="registro.php" class="nav-link text-white"><i class="bi bi-person-plus me-2"></i> Registrarse</a></li>
    <?php endif; ?>
  </ul>
</div>
