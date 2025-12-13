<?php
require 'auth_middleware.php';
require 'db.php';
include 'header.php';

$nombre   = $_GET['nombre'] ?? '';
$empresa  = $_GET['empresa'] ?? '';
$estatus  = $_GET['estatus'] ?? ''; // nuevo filtro

$sql = "SELECT * FROM proveedores WHERE 1=1";
$params = [];

if ($nombre !== '') {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%$nombre%";
}
if ($empresa !== '') {
    $sql .= " AND empresa LIKE ?";
    $params[] = "%$empresa%";
}
if ($estatus !== '') {
    $sql .= " AND estatus = ?";
    $params[] = $estatus;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proveedores = $stmt->fetchAll();
?>
<h1>Proveedores</h1>

<form method="get" class="filtros">
    <input type="text" name="nombre" placeholder="Nombre"
           value="<?php echo htmlspecialchars($nombre); ?>">

    <input type="text" name="empresa" placeholder="Empresa"
           value="<?php echo htmlspecialchars($empresa); ?>">

    <label>Estatus</label>
    <select name="estatus">
        <option value="">Todos</option>
        <option value="ACTIVO"   <?php echo $estatus === 'ACTIVO'   ? 'selected' : ''; ?>>ACTIVO</option>
        <option value="INACTIVO" <?php echo $estatus === 'INACTIVO' ? 'selected' : ''; ?>>INACTIVO</option>
    </select>

    <?php if ($_SESSION['rol'] === 'ADMINISTRADOR'): ?>
        <a class="btn" href="proveedores_form.php">Nuevo proveedor</a>
    <?php endif; ?>
    <button type="submit">Buscar</button>
</form>

<table class="tabla">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Empresa</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Categoría</th>
            <th>Estatus</th>
            <?php if ($_SESSION['rol'] === 'ADMINISTRADOR'): ?>
                <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($proveedores as $p): ?>
        <tr>
            <td><?php echo $p['id_proveedor']; ?></td>
            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
            <td><?php echo htmlspecialchars($p['empresa']); ?></td>
            <td><?php echo htmlspecialchars($p['telefono']); ?></td>
            <td><?php echo htmlspecialchars($p['correo']); ?></td>
            <td><?php echo htmlspecialchars($p['categoria']); ?></td>
            <td><?php echo htmlspecialchars($p['estatus']); ?></td>
            <?php if ($_SESSION['rol'] === 'ADMINISTRADOR'): ?>
            <td>
                <a href="proveedores_form.php?id=<?php echo $p['id_proveedor']; ?>">Editar</a>
                <?php if ($p['estatus'] === 'ACTIVO'): ?>
                    <a href="orden_proveedor_form.php?id_proveedor=<?php echo $p['id_proveedor']; ?>">Hacer pedido</a>
                <?php endif; ?>
            </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($proveedores)): ?>
        <tr><td colspan="8">No hay proveedores con ese filtro.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
