<?php
require 'auth_middleware.php';
require 'db.php';
include 'header.php';

$fecha_desde = $_GET['desde'] ?? '';
$fecha_hasta = $_GET['hasta'] ?? '';

$params = [];
$filtroFecha = '';

if ($fecha_desde !== '') {
    $filtroFecha .= " AND v.fecha >= ?";
    $params[] = $fecha_desde . ' 00:00:00';
}
if ($fecha_hasta !== '') {
    $filtroFecha .= " AND v.fecha <= ?";
    $params[] = $fecha_hasta . ' 23:59:59';
}

// Productos más vendidos 
$sqlTop = "SELECT p.id_producto,
                  p.nombre,
                  p.categoria,
                  SUM(d.cantidad) AS total_cantidad,
                  SUM(d.subtotal) AS total_monto
           FROM detalle_venta_prueba d
           JOIN ventas_prueba v ON d.id_venta = v.id_venta
           JOIN productos p ON d.id_producto = p.id_producto
           WHERE 1=1 $filtroFecha
           GROUP BY p.id_producto, p.nombre, p.categoria
           ORDER BY total_cantidad DESC
           LIMIT 10";
$stmtTop = $pdo->prepare($sqlTop);
$stmtTop->execute($params);
$masVendidos = $stmtTop->fetchAll();

// Productos menos vendidos 
$sqlLow = "SELECT p.id_producto,
                  p.nombre,
                  p.categoria,
                  SUM(d.cantidad) AS total_cantidad,
                  SUM(d.subtotal) AS total_monto
           FROM detalle_venta_prueba d
           JOIN ventas_prueba v ON d.id_venta = v.id_venta
           JOIN productos p ON d.id_producto = p.id_producto
           WHERE 1=1 $filtroFecha
           GROUP BY p.id_producto, p.nombre, p.categoria
           ORDER BY total_cantidad ASC
           LIMIT 10";
$stmtLow = $pdo->prepare($sqlLow);
$stmtLow->execute($params);
$menosVendidos = $stmtLow->fetchAll();
?>
<h1>Análisis de ventas (pruebas)</h1>

<form method="get" class="filtros">
    <label>Desde</label>
    <input type="date" name="desde" value="<?php echo htmlspecialchars($fecha_desde); ?>">

    <label>Hasta</label>
    <input type="date" name="hasta" value="<?php echo htmlspecialchars($fecha_hasta); ?>">

    <button type="submit">Filtrar</button>
</form>

<h2>Productos más vendidos</h2>
<?php if (empty($masVendidos)): ?>
    <p>No hay ventas en el periodo seleccionado.</p>
<?php else: ?>
<table class="tabla">
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Cantidad vendida</th>
            <th>Monto total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($masVendidos as $p): ?>
        <tr>
            <td><?php echo $p['id_producto']; ?></td>
            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
            <td><?php echo htmlspecialchars($p['categoria']); ?></td>
            <td><?php echo $p['total_cantidad']; ?></td>
            <td><?php echo number_format($p['total_monto'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<h2>Productos menos vendidos</h2>
<?php if (empty($menosVendidos)): ?>
    <p>No hay ventas en el periodo seleccionado.</p>
<?php else: ?>
<table class="tabla">
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Cantidad vendida</th>
            <th>Monto total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($menosVendidos as $p): ?>
        <tr>
            <td><?php echo $p['id_producto']; ?></td>
            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
            <td><?php echo htmlspecialchars($p['categoria']); ?></td>
            <td><?php echo $p['total_cantidad']; ?></td>
            <td><?php echo number_format($p['total_monto'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php include 'footer.php'; ?>
