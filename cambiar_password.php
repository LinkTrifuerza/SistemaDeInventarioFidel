<?php
require 'auth_middleware.php';
require 'db.php';
include 'header.php';

$id = $_SESSION['id_empleado'];
$errores = [];
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['password_actual'] ?? '';
    $nueva1 = $_POST['password_nueva'] ?? '';
    $nueva2 = $_POST['password_nueva2'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT password_hash
         FROM empleados
         WHERE id_empleado = ?"
    );
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($actual, $user['password_hash'])) {
        $errores[] = 'La contraseña actual es incorrecta';
    }

    if ($nueva1 !== $nueva2) {
        $errores[] = 'Las contraseñas nuevas no coinciden';
    }

    if (!preg_match('/[A-Z]/', $nueva1) ||
        !preg_match('/[a-z]/', $nueva1) ||
        !preg_match('/[0-9]/', $nueva1)) {
        $errores[] = 'La contraseña debe tener mayúsculas, minúsculas y números';
    }

    if (empty($errores)) {
        $hash = password_hash($nueva1, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "UPDATE empleados
             SET password_hash = ?
             WHERE id_empleado = ?"
        );
        $stmt->execute([$hash, $id]);
        $exito = 'Contraseña actualizada correctamente.';
    }
}
?>
<h1>Cambiar contraseña</h1>

<?php foreach ($errores as $e): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($e); ?></div>
<?php endforeach; ?>

<?php if ($exito): ?>
    <div class="alert"><?php echo htmlspecialchars($exito); ?></div>
<?php endif; ?>

<form method="post" class="form">
    <label>Contraseña actual</label>
    <input type="password" name="password_actual" required>

    <label>Nueva contraseña</label>
    <input type="password" name="password_nueva" required>

    <label>Repetir nueva contraseña</label>
    <input type="password" name="password_nueva2" required>

    <button type="submit">Guardar</button>
    <a class="btn" href="perfil.php">Volver a perfil</a>
</form>
<?php include 'footer.php'; ?>
