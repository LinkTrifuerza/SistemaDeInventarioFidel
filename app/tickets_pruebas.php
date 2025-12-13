<?php
require 'auth_middleware.php';
require 'db.php';
include 'header.php';

$fecha_desde = $_GET['desde'] ?? '';
$fecha_hasta = $_GET['hasta'] ?? '';
$cliente     = $_GET['cliente'] ?? '';

$sql = "SELECT v.*, e.nombre AS empleado
        FROM ventas_prueba v
        JOIN empleados e ON v.id_empleado = e.id_empleado
        WHERE 1=1";
$params = [];

if ($fecha_desde !== '') {
    $sql .= " AND v.fecha >= ?";
    $params[] = $fecha_desde . ' 00:00:00';
}
if ($fecha_hasta !== '') {
    $sql .= " AND v.fecha <= ?";
    $params[] = $fecha_hasta . ' 23:59:59';
}
if ($cliente !== '') {
    $sql .= " AND v.nombre_cliente LIKE ?";
    $params[] = '%' . $cliente . '%';
}

$sql .= " ORDER BY v.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll();

// Traer detalle de todas las ventas listadas
$detalles_por_venta = [];
if (!empty($ventas)) {
    $ids = array_column($ventas, 'id_venta');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sqlDet = "SELECT d.id_venta, d.cantidad, d.precio_unitario, d.subtotal,
                      p.nombre AS producto
               FROM detalle_venta_prueba d
               JOIN productos p ON d.id_producto = p.id_producto
               WHERE d.id_venta IN ($placeholders)
               ORDER BY d.id_venta";
    $stmtDet = $pdo->prepare($sqlDet);
    $stmtDet->execute($ids);
    $detalles = $stmtDet->fetchAll();

    foreach ($detalles as $det) {
        $detalles_por_venta[$det['id_venta']][] = $det;
    }
}
?>
<h1>Tickets de ventas de prueba</h1>

<form method="get" class="filtros">
    <label>Desde</label>
    <input type="date" name="desde" value="<?php echo htmlspecialchars($fecha_desde); ?>">

    <label>Hasta</label>
    <input type="date" name="hasta" value="<?php echo htmlspecialchars($fecha_hasta); ?>">

    <label>Cliente</label>
    <input type="text" name="cliente" value="<?php echo htmlspecialchars($cliente); ?>">

    <button type="submit">Filtrar</button>
</form>

<?php if (empty($ventas)): ?>
    <p>No hay tickets de pruebas.</p>
<?php else: ?>
    <?php foreach ($ventas as $v): ?>
        <div class="card">
            <h2>Venta prueba #<?php echo $v['id_venta']; ?></h2>
            <p><strong>Fecha:</strong> <?php echo $v['fecha']; ?></p>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($v['nombre_cliente']); ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($v['total'], 2); ?></p>
            <p><strong>Empleado:</strong> <?php echo htmlspecialchars($v['empleado']); ?></p>

            <h3>Productos vendidos</h3>
            <?php if (!empty($detalles_por_venta[$v['id_venta']] ?? [])): ?>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles_por_venta[$v['id_venta']] as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['producto']); ?></td>
                                <td><?php echo $d['cantidad']; ?></td>
                                <td><?php echo number_format($d['precio_unitario'], 2); ?></td>
                                <td><?php echo number_format($d['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay detalle de productos.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php include 'footer.php'; ?>
