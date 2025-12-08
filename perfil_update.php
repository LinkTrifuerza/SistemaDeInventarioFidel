<?php
require 'auth_middleware.php';
require 'db.php';

$id = $_SESSION['id_empleado'];
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';

$stmt = $pdo->prepare(
    "UPDATE empleados
     SET nombre=?, apellido=?, correo=?, telefono=?
     WHERE id_empleado=?"
);
$stmt->execute([$nombre, $apellido, $correo, $telefono, $id]);

header('Location: perfil.php');
