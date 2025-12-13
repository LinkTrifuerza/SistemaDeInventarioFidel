<?php
require_once 'stock_notificaciones.php';

$id_empleado = $_SESSION['id_empleado'] ?? null;
$notificaciones = obtener_notificaciones_no_leidas($pdo, $id_empleado);
$contador_notif = count($notificaciones);
?>
<nav class="navbar">
  <div class="navbar-left">
    <a href="dashboard.php">Inicio</a>
    <a href="inventario_list.php">Inventario</a>
    <a href="proveedores_list.php">Proveedores</a>
    <a href="tickets_ordenes.php">Tickets ordenes</a>
<a href="tickets_pruebas.php">Tickets ventas</a> 
    <a href="empleados_list.php">Empleados</a>
     <a href="pruebas_carrito.php">Generar ventas (Pruebas)</a>
     <a href="analisis_ventas.php">Análisis de ventas</a>
  </div>
  <div class="navbar-right">
    <div class="notificaciones">
      <a href="notificaciones_list.php">
        🔔 (<?php echo $contador_notif; ?>)
      </a>
    </div>
    <div class="perfil">
      <a href="perfil.php">
        <?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?>
      </a>
    </div>
    <div class="logout">
      <a href="logout.php">Cerrar sesión</a>
    </div>
  </div>
</nav>
