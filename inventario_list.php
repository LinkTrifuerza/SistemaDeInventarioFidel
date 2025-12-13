<?php
require 'auth_middleware.php';
require 'db.php';

$nombre = $_GET['nombre'] ?? '';
$categoria = $_GET['categoria'] ?? '';

$sql = "SELECT p.*, pr.nombre AS proveedor
        FROM productos p
        JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
        WHERE p.estatus = 'ACTIVO'";
$params = [];

if ($nombre !== '') {
    $sql .= " AND p.nombre LIKE ?";
    $params[] = "%$nombre%";
}
if ($categoria !== '') {
    $sql .= " AND p.categoria = ?";
    $params[] = $categoria;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();

include 'header.php';
?>
<h1>Inventario</h1>

<form method="get" class="filtros">
    <input type="text" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($nombre); ?>">
    <input type="text" name="categoria" placeholder="Categoría" value="<?php echo htmlspecialchars($categoria); ?>">
    <button type="submit">Buscar</button>
    <?php if ($_SESSION['rol'] === 'ADMINISTRADOR'): ?>
        <a class="btn" href="inventario_form.php">Nuevo producto</a>
    <?php endif; ?>
</form>

<table class="tabla">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Proveedor</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Mínimo</th>
            <th>Máximo</th>
            <?php if ($_SESSION['rol'] === 'ADMINISTRADOR'): ?>
                <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $p): ?>
        <tr>
            <td><?php echo $p['id_producto']; ?></td>
            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
            <td><?php echo htmlspecialchars($p['categoria']); ?></td>
            <td><?php echo htmlspecialchars($p['proveedor']); ?></td>
            <td><?php echo number_format($p['precio'], 2); ?></td>
            <td><?php echo $p['stock_actual']; ?></td>
            <td><?php echo $p['stock_minimo']; ?></td>
            <td><?php echo $p['stock_maximo']; ?></td>
            <?php if ($_SESSION['rol'] === 'ADMINISTRADOR'): ?>
            <td>
                <a href="inventario_form.php?id=<?php echo $p['id_producto']; ?>">Editar</a>
                <a href="inventario_delete.php?id=<?php echo $p['id_producto']; ?>" onclick="return confirm('¿Marcar producto como inactivo?');">Inactivar</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($productos)): ?>
        <tr><td colspan="9">No hay productos activos.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
