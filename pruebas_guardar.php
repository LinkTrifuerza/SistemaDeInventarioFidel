<?php
require 'auth_middleware.php';
require 'db.php';
require 'stock_notificaciones.php';

if (empty($_SESSION['carrito'])) {
    header('Location: pruebas_carrito.php');
    exit;
}

$nombre_cliente = $_POST['nombre_cliente'] ?? 'CLIENTE PRUEBA';
$id_empleado = $_SESSION['id_empleado'];

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$pdo->beginTransaction();

$stmt = $pdo->prepare(
    "INSERT INTO ventas_prueba (total, nombre_cliente, id_empleado)
     VALUES (?,?,?)"
);
$stmt->execute([$total, $nombre_cliente, $id_empleado]);
$id_venta = (int)$pdo->lastInsertId();

$stmt_det = $pdo->prepare(
    "INSERT INTO detalle_venta_prueba
     (id_venta, id_producto, cantidad, precio_unitario, subtotal)
     VALUES (?,?,?,?,?)"
);

$stmt_stock = $pdo->prepare(
    "UPDATE productos
     SET stock_actual = stock_actual - ?
     WHERE id_producto = ?"
);

$stmt_mov = $pdo->prepare(
    "INSERT INTO movimientos_stock
     (id_producto, tipo, cantidad, motivo, referencia)
     VALUES (?, 'SALIDA', ?, 'VENTA_PRUEBA', ?)"
);

foreach ($_SESSION['carrito'] as $item) {
    $idp = (int)$item['id_producto'];
    $cantidad = (int)$item['cantidad'];
    $precio = (float)$item['precio'];
    $subtotal = $precio * $cantidad;

    $stmt_det->execute([
        $id_venta,
        $idp,
        $cantidad,
        $precio,
        $subtotal
    ]);

    $stmt_stock->execute([$cantidad, $idp]);

    $stmt_mov->execute([
        $idp,
        $cantidad,
        'VENTA-' . $id_venta
    ]);

    verificar_stock_y_notificar($pdo, $idp);
}

$pdo->commit();
unset($_SESSION['carrito']);

header('Location: tickets_pruebas.php');
