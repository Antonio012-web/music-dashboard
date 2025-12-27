<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"]);
  $email = trim($_POST["email"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nombre, $email, $password);

  if ($stmt->execute()) {
    $_SESSION["usuario_id"] = $stmt->insert_id;
    $_SESSION["usuario"] = $nombre;
    header("Location: index.php");
    exit();
  } else {
    $error = "El correo ya está registrado.";
  }
}
?>

<?php include("includes/header.php"); ?>
<div class="container py-5">
  <div class="col-md-5 mx-auto bg-light p-4 shadow rounded">
    <h4 class="text-center mb-4">📝 Registrarse</h4>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Correo electrónico</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Crear cuenta</button>
    </form>
  </div>
</div>
<?php include("includes/footer.php"); ?>
