<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';
require 'stock_notificaciones.php';

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$precio = $_POST['precio'] ?? 0;
$stock_actual = (int)($_POST['stock_actual'] ?? 0);
$stock_minimo = (int)($_POST['stock_minimo'] ?? 0);
$stock_maximo = (int)($_POST['stock_maximo'] ?? 0);
$id_proveedor = $_POST['id_proveedor'] ?? '';
$costo_total_inicial = isset($_POST['costo_total_inicial']) ? (float)$_POST['costo_total_inicial'] : 0.0;
$empresa_emisora_inicial = $_POST['empresa_emisora_inicial'] ?? 'ALTA INICIAL';

// Datos de posible proveedor nuevo
$prov_nombre    = trim($_POST['prov_nombre'] ?? '');
$prov_empresa   = trim($_POST['prov_empresa'] ?? '');
$prov_telefono  = trim($_POST['prov_telefono'] ?? '');
$prov_correo    = trim($_POST['prov_correo'] ?? '');
$prov_direccion = trim($_POST['prov_direccion'] ?? '');
$prov_categoria = trim($_POST['prov_categoria'] ?? '');

// Si no se seleccionó proveedor existente pero se puso nombre nuevo, se crea el proveedor
if ($id_proveedor === '' && $prov_nombre !== '') {
    $stmtProv = $pdo->prepare(
        "INSERT INTO proveedores (nombre, empresa, telefono, correo, direccion, categoria, estatus)
         VALUES (?,?,?,?,?,?, 'ACTIVO')"
    );
    $stmtProv->execute([
        $prov_nombre,
        $prov_empresa,
        $prov_telefono,
        $prov_correo,
        $prov_direccion,
        $prov_categoria
    ]);
    $id_proveedor = $pdo->lastInsertId();
}

// Si seguimos sin proveedor, se detiene
if ($id_proveedor === '') {
    die('Debe seleccionar o registrar un proveedor.');
}

if ($id) {
    // EDICIÓN
    $stmt = $pdo->prepare(
        "UPDATE productos
         SET nombre=?, categoria=?, descripcion=?, precio=?, stock_actual=?,
             stock_minimo=?, stock_maximo=?, id_proveedor=?
         WHERE id_producto=?"
    );
    $stmt->execute([
        $nombre, $categoria, $descripcion, $precio, $stock_actual,
        $stock_minimo, $stock_maximo, $id_proveedor, $id
    ]);

    verificar_stock_y_notificar($pdo, (int)$id);
} else {
    // ALTA NUEVA
    $pdo->beginTransaction();

    // Crear producto
    $stmt = $pdo->prepare(
        "INSERT INTO productos
         (nombre, categoria, descripcion, precio, stock_actual, stock_minimo, stock_maximo, id_proveedor, estatus)
         VALUES (?,?,?,?,?,?,?,?, 'ACTIVO')"
    );
    $stmt->execute([
        $nombre, $categoria, $descripcion, $precio, $stock_actual,
        $stock_minimo, $stock_maximo, $id_proveedor
    ]);
    $nuevoId = (int)$pdo->lastInsertId();

    // Si el stock inicial > 0, generar una ORDEN INICIAL con monto y costo unitario calculados
    if ($stock_actual > 0) {
        $id_empleado = $_SESSION['id_empleado'];

        $monto_total = max(0, $costo_total_inicial);

        $costo_unitario = 0;
        if ($monto_total > 0 && $stock_actual > 0) {
            $costo_unitario = $monto_total / $stock_actual;
        }

        $stmtOrd = $pdo->prepare(
            "INSERT INTO ordenes_proveedor
             (id_proveedor, monto_total, nombre_empresa_emisora, id_empleado, tipo_orden)
             VALUES (?,?,?,?, 'INICIAL')"
        );
        $stmtOrd->execute([
            $id_proveedor,
            $monto_total,
            $empresa_emisora_inicial,
            $id_empleado
        ]);
        $id_orden = (int)$pdo->lastInsertId();

        $stmtDet = $pdo->prepare(
            "INSERT INTO detalle_orden_proveedor
             (id_orden, id_producto, cantidad, costo_unitario, subtotal)
             VALUES (?,?,?,?,?)"
        );
        $stmtDet->execute([
            $id_orden,
            $nuevoId,
            $stock_actual,
            $costo_unitario,
            $monto_total
        ]);

        $stmtMov = $pdo->prepare(
            "INSERT INTO movimientos_stock
             (id_producto, tipo, cantidad, motivo, referencia)
             VALUES (?, 'ENTRADA', ?, 'ALTA_INICIAL', ?)"
        );
        $stmtMov->execute([
            $nuevoId,
            $stock_actual,
            'ORDEN-' . $id_orden
        ]);
    }

    $pdo->commit();

    verificar_stock_y_notificar($pdo, $nuevoId);
}

header('Location: inventario_list.php');
