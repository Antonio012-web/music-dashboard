<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $clave = $_POST["password"];

  $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();

  if ($user && password_verify($clave, $user["password"])) {
    $_SESSION["usuario_id"] = $user["id"];
    $_SESSION["usuario"] = $user["nombre"];
    header("Location: index.php");
    exit();
  } else {
    $error = "Correo o contraseña incorrectos.";
  }
}
?>

<?php include("includes/header.php"); ?>
<div class="container py-5">
  <div class="col-md-5 mx-auto bg-light p-4 shadow rounded">
    <h4 class="text-center mb-4">🔐 Iniciar Sesión</h4>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Correo electrónico</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
  </div>
</div>
<?php include("includes/footer.php"); ?>
