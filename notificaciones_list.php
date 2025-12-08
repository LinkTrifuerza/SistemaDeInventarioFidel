<?php
require 'auth_middleware.php';
require 'db.php';
require 'stock_notificaciones.php';
include 'header.php';

$id_empleado = $_SESSION['id_empleado'] ?? null;
$notificaciones = obtener_notificaciones_no_leidas($pdo, $id_empleado);

$stmt = $pdo->prepare(
    "UPDATE notificaciones
     SET leida = 1
     WHERE (id_empleado_destino IS NULL OR id_empleado_destino = ?)
     AND leida = 0"
);
$stmt->execute([$id_empleado]);
?>
<h1>Notificaciones</h1>

<?php if (empty($notificaciones)): ?>
    <p>No hay notificaciones nuevas.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Mensaje</th>
                <th>ID producto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notificaciones as $n): ?>
                <tr>
                    <td><?php echo $n['fecha_creacion']; ?></td>
                    <td><?php echo htmlspecialchars($n['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($n['mensaje']); ?></td>
                    <td><?php echo $n['id_producto']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php include 'footer.php'; ?>
