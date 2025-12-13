<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare(
        "UPDATE productos
         SET estatus = 'INACTIVO'
         WHERE id_producto = ?"
    );
    $stmt->execute([$id]);
}
header('Location: inventario_list.php');
