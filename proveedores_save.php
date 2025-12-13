<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';

$id        = $_POST['id'] ?? null;
$nombre    = $_POST['nombre'] ?? '';
$empresa   = $_POST['empresa'] ?? '';
$telefono  = $_POST['telefono'] ?? '';
$correo    = $_POST['correo'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$estatus   = $_POST['estatus'] ?? 'ACTIVO';

if ($id) {
    $stmt = $pdo->prepare(
        "UPDATE proveedores
         SET nombre=?, empresa=?, telefono=?, correo=?, direccion=?, categoria=?, estatus=?
         WHERE id_proveedor=?"
    );
    $stmt->execute([$nombre, $empresa, $telefono, $correo, $direccion, $categoria, $estatus, $id]);
} else {
    $stmt = $pdo->prepare(
        "INSERT INTO proveedores (nombre, empresa, telefono, correo, direccion, categoria, estatus)
         VALUES (?,?,?,?,?,?,?)"
    );
    $stmt->execute([$nombre, $empresa, $telefono, $correo, $direccion, $categoria, $estatus]);
}

header('Location: proveedores_list.php');
