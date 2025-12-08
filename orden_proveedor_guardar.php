<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';
require 'stock_notificaciones.php';

$id_proveedor = $_POST['id_proveedor'] ?? null;
$nombre_empresa_emisora = $_POST['nombre_empresa_emisora'] ?? '';
$id_empleado = $_SESSION['id_empleado'];

$id_productos = $_POST['id_producto'] ?? [];
$cantidades   = $_POST['cantidad'] ?? [];
$costos       = $_POST['costo'] ?? [];

if (!$id_proveedor || empty($id_productos)) {
    header('Location: orden_proveedor_form.php');
    exit;
}

$total = 0;
foreach ($id_productos as $i => $idp) {
    $cantidad = (int)$cantidades[$i];
    $costo    = (float)$costos[$i];
    if ($cantidad > 0) {
        $total += $cantidad * $costo;
    }
}

$pdo->beginTransaction();

$stmt = $pdo->prepare(
    "INSERT INTO ordenes_proveedor
     (id_proveedor, monto_total, nombre_empresa_emisora, id_empleado, tipo_orden)
     VALUES (?,?,?,?, 'NORMAL')"
);
$stmt->execute([$id_proveedor, $total, $nombre_empresa_emisora, $id_empleado]);
$id_orden = (int)$pdo->lastInsertId();

$stmt_det = $pdo->prepare(
    "INSERT INTO detalle_orden_proveedor
     (id_orden, id_producto, cantidad, costo_unitario, subtotal)
     VALUES (?,?,?,?,?)"
);

$stmt_stock = $pdo->prepare(
    "UPDATE productos
     SET stock_actual = stock_actual + ?
     WHERE id_producto = ?"
);

$stmt_mov = $pdo->prepare(
    "INSERT INTO movimientos_stock
     (id_producto, tipo, cantidad, motivo, referencia)
     VALUES (?, 'ENTRADA', ?, 'ORDEN_PROVEEDOR', ?)"
);

foreach ($id_productos as $i => $idp) {
    $idp = (int)$idp;
    $cantidad = (int)$cantidades[$i];
    $costo    = (float)$costos[$i];

    if ($cantidad <= 0) {
        continue;
    }

    $subtotal = $cantidad * $costo;

    $stmt_det->execute([
        $id_orden,
        $idp,
        $cantidad,
        $costo,
        $subtotal
    ]);

    $stmt_stock->execute([$cantidad, $idp]);

    $stmt_mov->execute([
        $idp,
        $cantidad,
        'ORDEN-' . $id_orden
    ]);

    verificar_stock_y_notificar($pdo, $idp);
}

$pdo->commit();

header('Location: tickets_ordenes.php');
