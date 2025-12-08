<?php
require 'auth_middleware.php';
require 'db.php';
include 'header.php';

$id = $_SESSION['id_empleado'];

$stmt = $pdo->prepare(
    "SELECT nombre, apellido, usuario, correo, telefono, rol
     FROM empleados
     WHERE id_empleado = ?"
);
$stmt->execute([$id]);
$empleado = $stmt->fetch();
?>
<h1>Mi perfil</h1>

<?php if ($empleado): ?>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?></p>
    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($empleado['usuario']); ?></p>
    <p><strong>Correo:</strong> <?php echo htmlspecialchars($empleado['correo']); ?></p>
    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($empleado['telefono']); ?></p>
    <p><strong>Rol:</strong> <?php echo htmlspecialchars($empleado['rol']); ?></p>

    <h2>Editar datos</h2>
    <form method="post" action="perfil_update.php" class="form">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>

        <label>Apellido</label>
        <input type="text" name="apellido" value="<?php echo htmlspecialchars($empleado['apellido']); ?>" required>

        <label>Correo</label>
        <input type="email" name="correo" value="<?php echo htmlspecialchars($empleado['correo']); ?>" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($empleado['telefono']); ?>">

        <button type="submit">Guardar cambios</button>
    </form>

    <h2>Cambiar contraseña</h2>
    <a class="btn" href="cambiar_password.php">Ir a cambio de contraseña</a>
<?php else: ?>
    <p>No se encontró el usuario.</p>
<?php endif; ?>

<?php include 'footer.php'; ?>
