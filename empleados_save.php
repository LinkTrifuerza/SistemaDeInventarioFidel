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
$stock_actual = $_POST['stock_actual'] ?? 0;
$stock_minimo = $_POST['stock_minimo'] ?? 0;
$stock_maximo = $_POST['stock_maximo'] ?? 0;
$id_proveedor = $_POST['id_proveedor'] ?? null;

if ($id) {
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
    $stmt = $pdo->prepare(
        "INSERT INTO productos
         (nombre, categoria, descripcion, precio, stock_actual, stock_minimo, stock_maximo, id_proveedor)
         VALUES (?,?,?,?,?,?,?,?)"
    );
    $stmt->execute([
        $nombre, $categoria, $descripcion, $precio, $stock_actual,
        $stock_minimo, $stock_maximo, $id_proveedor
    ]);
    $nuevoId = (int)$pdo->lastInsertId();
    verificar_stock_y_notificar($pdo, $nuevoId);
}

header('Location: empleados_list.php');
