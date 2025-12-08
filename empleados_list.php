<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';
include 'header.php';

$rol      = $_GET['rol'] ?? '';
$usuario  = $_GET['usuario'] ?? '';
$correo   = $_GET['correo'] ?? '';
$telefono = $_GET['telefono'] ?? '';

$sql = "SELECT * FROM empleados WHERE 1=1";
$params = [];

if ($rol !== '') {
    $sql .= " AND rol = ?";
    $params[] = $rol;
}
if ($usuario !== '') {
    $sql .= " AND usuario LIKE ?";
    $params[] = '%' . $usuario . '%';
}
if ($correo !== '') {
    $sql .= " AND correo LIKE ?";
    $params[] = '%' . $correo . '%';
}
if ($telefono !== '') {
    $sql .= " AND telefono LIKE ?";
    $params[] = '%' . $telefono . '%';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$empleados = $stmt->fetchAll();
?>
<h1>Empleados</h1>

<form method="get" class="filtros">
    <label>Rol</label>
    <select name="rol">
        <option value="">Todos</option>
        <option value="ADMINISTRADOR" <?php echo $rol === 'ADMINISTRADOR' ? 'selected' : ''; ?>>ADMINISTRADOR</option>
        <option value="EMPLEADO" <?php echo $rol === 'EMPLEADO' ? 'selected' : ''; ?>>EMPLEADO</option>
    </select>

    <input type="text" name="usuario" placeholder="Usuario" value="<?php echo htmlspecialchars($usuario); ?>">
    <input type="text" name="correo" placeholder="Correo" value="<?php echo htmlspecialchars($correo); ?>">
    <input type="text" name="telefono" placeholder="Teléfono" value="<?php echo htmlspecialchars($telefono); ?>">

    <button type="submit">Filtrar</button>
    <a class="btn" href="empleados_form.php">Nuevo empleado</a>
</form>

<table class="tabla">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Rol</th>
            <th>Estatus</th>
            <th>Puesto</th>
            <th>Horario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($empleados as $e): ?>
        <tr>
            <td><?php echo $e['id_empleado']; ?></td>
            <td><?php echo htmlspecialchars($e['nombre'] . ' ' . $e['apellido']); ?></td>
            <td><?php echo htmlspecialchars($e['usuario']); ?></td>
            <td><?php echo htmlspecialchars($e['correo']); ?></td>
            <td><?php echo htmlspecialchars($e['telefono']); ?></td>
            <td><?php echo htmlspecialchars($e['rol']); ?></td>
            <td><?php echo htmlspecialchars($e['estatus']); ?></td>
            <td><?php echo htmlspecialchars($e['puesto']); ?></td>
            <td><?php echo htmlspecialchars($e['horario']); ?></td>
<td>
    <a href="empleados_form.php?id=<?php echo $e['id_empleado']; ?>">Editar</a>
</td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($empleados)): ?>
        <tr><td colspan="10">No hay empleados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
