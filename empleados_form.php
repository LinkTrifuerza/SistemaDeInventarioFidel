<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';
include 'header.php';

$id = $_GET['id'] ?? null;
$empleado = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id_empleado = ?");
    $stmt->execute([$id]);
    $empleado = $stmt->fetch();
}
?>
<h1><?php echo $id ? 'Editar empleado' : 'Nuevo empleado'; ?></h1>

<form method="post" action="empleados_save.php" class="form">
    <input type="hidden" name="id" value="<?php echo $empleado['id_empleado'] ?? ''; ?>">

    <label>Nombre</label>
    <input type="text" name="nombre" required value="<?php echo htmlspecialchars($empleado['nombre'] ?? ''); ?>">

    <label>Apellido</label>
    <input type="text" name="apellido" required value="<?php echo htmlspecialchars($empleado['apellido'] ?? ''); ?>">

    <label>Usuario</label>
    <input type="text" name="usuario" required value="<?php echo htmlspecialchars($empleado['usuario'] ?? ''); ?>">

    <label>Correo</label>
    <input type="email" name="correo" required value="<?php echo htmlspecialchars($empleado['correo'] ?? ''); ?>">

    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?php echo htmlspecialchars($empleado['telefono'] ?? ''); ?>">

    <label>Puesto</label>
    <input type="text" name="puesto" value="<?php echo htmlspecialchars($empleado['puesto'] ?? ''); ?>">

    <label>Horario</label>
    <input type="text" name="horario" value="<?php echo htmlspecialchars($empleado['horario'] ?? ''); ?>">

    <label>Rol</label>
    <select name="rol">
        <option value="ADMINISTRADOR" <?php echo (($empleado['rol'] ?? '') === 'ADMINISTRADOR') ? 'selected' : ''; ?>>ADMINISTRADOR</option>
        <option value="EMPLEADO" <?php echo (($empleado['rol'] ?? 'EMPLEADO') === 'EMPLEADO') ? 'selected' : ''; ?>>EMPLEADO</option>
    </select>

    <label>Estatus</label>
    <select name="estatus">
        <option value="ACTIVO" <?php echo (($empleado['estatus'] ?? '') === 'ACTIVO') ? 'selected' : ''; ?>>ACTIVO</option>
        <option value="INACTIVO" <?php echo (($empleado['estatus'] ?? '') === 'INACTIVO') ? 'selected' : ''; ?>>INACTIVO</option>
    </select>

    <label>Contraseña <?php echo $id ? '(deja vacío para no cambiar)' : ''; ?></label>
    <input type="password" name="password">

    <button type="submit">Guardar</button>
    <a class="btn" href="empleados_list.php">Cancelar</a>
</form>
<?php include 'footer.php'; ?>
