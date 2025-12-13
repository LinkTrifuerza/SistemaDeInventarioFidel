<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Inventario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php if (isset($_SESSION['id_empleado'])): ?>
    <?php include 'navbar.php'; ?>
<?php endif; ?>
<main class="container">
