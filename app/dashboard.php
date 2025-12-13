<?php
require 'auth_middleware.php';
include 'header.php';
?>
<h1>Panel principal</h1>

<p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>.</p>

<div class="cards">
    <a class="card" href="inventario_list.php">
        <span>Inventario</span>
        <img src="img/inventario.png" alt="Inventario">
    </a>

    <a class="card" href="proveedores_list.php">
        <span>Proveedores</span>
        <img src="img/proveedores.png" alt="Proveedores">
    </a>

    <a class="card" href="pruebas_carrito.php">
        <span>Pruebas de venta</span>
        <img src="img/pruebas.png" alt="Pruebas de venta">
    </a>

    <a class="card" href="analisis_ventas.php">
        <span>Análisis ventas</span>
        <img src="img/analisis.png" alt="Análisis ventas">
    </a>

    <a class="card" href="empleados_list.php">
        <span>Empleados</span>
        <img src="img/empleados.png" alt="Empleados">
    </a>
    <a class="card" href="tickets_ordenes.php">
        <span>Tickets órdenes</span>
        <img src="img/tickets_ordenes.png" alt="Tickets órdenes">
    </a>

    <a class="card" href="tickets_pruebas.php">
        <span>Tickets pruebas</span>
        <img src="img/tickets_pruebas.png" alt="Tickets pruebas">
    </a>


</div>
<?php include 'footer.php'; ?>
