<?php
require 'auth_middleware.php';
require 'db.php';
include 'header.php';

$fecha_desde  = $_GET['desde'] ?? '';
$fecha_hasta  = $_GET['hasta'] ?? '';
$id_proveedor = $_GET['id_proveedor'] ?? '';

$sql = "SELECT o.*, p.nombre AS proveedor, e.nombre AS empleado
        FROM ordenes_proveedor o
        JOIN proveedores p ON o.id_proveedor = p.id_proveedor
        JOIN empleados e ON o.id_empleado = e.id_empleado
        WHERE 1=1";
$params = [];

if ($fecha_desde !== '') {
    $sql .= " AND o.fecha >= ?";
    $params[] = $fecha_desde . ' 00:00:00';
}
if ($fecha_hasta !== '') {
    $sql .= " AND o.fecha <= ?";
    $params[] = $fecha_hasta . ' 23:59:59';
}
if ($id_proveedor !== '') {
    $sql .= " AND o.id_proveedor = ?";
    $params[] = $id_proveedor;
}

$sql .= " ORDER BY o.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ordenes = $stmt->fetchAll();

$proveedores = $pdo->query(
    "SELECT id_proveedor, nombre
     FROM proveedores
     ORDER BY nombre"
)->fetchAll();

// Detalle de todos los tickets
$detalles_por_orden = [];
if (!empty($ordenes)) {
    $ids = array_column($ordenes, 'id_orden');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sqlDet = "SELECT d.id_orden, d.cantidad, d.costo_unitario, d.subtotal,
                      pr.nombre AS producto
               FROM detalle_orden_proveedor d
               JOIN productos pr ON d.id_producto = pr.id_producto
               WHERE d.id_orden IN ($placeholders)
               ORDER BY d.id_orden";
    $stmtDet = $pdo->prepare($sqlDet);
    $stmtDet->execute($ids);
    $detalles = $stmtDet->fetchAll();

    foreach ($detalles as $det) {
        $detalles_por_orden[$det['id_orden']][] = $det;
    }
}
?>
<h1>Tickets de órdenes a proveedores</h1>

<form method="get" class="filtros">
    <label>Desde</label>
    <input type="date" name="desde" value="<?php echo htmlspecialchars($fecha_desde); ?>">

    <label>Hasta</label>
    <input type="date" name="hasta" value="<?php echo htmlspecialchars($fecha_hasta); ?>">

    <label>Proveedor</label>
    <select name="id_proveedor">
        <option value="">Todos</option>
        <?php foreach ($proveedores as $p): ?>
            <option value="<?php echo $p['id_proveedor']; ?>"
                <?php echo ($id_proveedor == $p['id_proveedor']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($p['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filtrar</button>
</form>

<?php if (empty($ordenes)): ?>
    <p>No hay tickets de órdenes.</p>
<?php else: ?>
    <?php foreach ($ordenes as $o): ?>
        <div class="card">
            <h2>Orden #<?php echo $o['id_orden']; ?></h2>
            <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($o['proveedor']); ?></p>
            <p><strong>Empresa emisora:</strong> <?php echo htmlspecialchars($o['nombre_empresa_emisora']); ?></p>
            <p><strong>Tipo de orden:</strong> <?php echo htmlspecialchars($o['tipo_orden']); ?></p>
            <p><strong>Fecha:</strong> <?php echo $o['fecha']; ?></p>
            <p><strong>Monto total:</strong> $<?php echo number_format($o['monto_total'], 2); ?></p>
            <p><strong>Empleado:</strong> <?php echo htmlspecialchars($o['empleado']); ?></p>

            <h3>Productos de esta orden</h3>
            <?php if (!empty($detalles_por_orden[$o['id_orden']] ?? [])): ?>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles_por_orden[$o['id_orden']] as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['producto']); ?></td>
                                <td><?php echo $d['cantidad']; ?></td>
                                <td><?php echo number_format($d['costo_unitario'], 2); ?></td>
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
