<?php
require 'auth_middleware.php';
require 'db.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = (int)$_POST['id_producto'];
    $cantidad = (int)$_POST['cantidad'];

    $stmt = $pdo->prepare(
        "SELECT id_producto, nombre, precio
         FROM productos
         WHERE id_producto = ? AND estatus = 'ACTIVO'"
    );
    $stmt->execute([$id_producto]);
    $prod = $stmt->fetch();

    if ($prod && $cantidad > 0) {
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id_producto'] == $id_producto) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id_producto' => $prod['id_producto'],
                'nombre'      => $prod['nombre'],
                'precio'      => $prod['precio'],
                'cantidad'    => $cantidad
            ];
        }
    }
}

$productos = $pdo->query(
    "SELECT id_producto, nombre, precio
     FROM productos
     WHERE stock_actual > 0 AND estatus = 'ACTIVO'"
)->fetchAll();

include 'header.php';
?>
<h1>Pruebas de venta</h1>

<section>
    <h2>Agregar producto al carrito</h2>
    <form method="post" class="form-inline">
        <label>Producto</label>
        <select name="id_producto" required>
            <option value="">Seleccione...</option>
            <?php foreach ($productos as $p): ?>
                <option value="<?php echo $p['id_producto']; ?>">
                    <?php echo htmlspecialchars($p['nombre']); ?> - $<?php echo number_format($p['precio'], 2); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Cantidad</label>
        <input type="number" name="cantidad" min="1" required>

        <button type="submit">Agregar</button>
    </form>
</section>

<section>
    <h2>Carrito</h2>
    <?php if (empty($_SESSION['carrito'])): ?>
        <p>No hay productos en el carrito.</p>
    <?php else: ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <?php $sub = $item['precio'] * $item['cantidad']; $total += $sub; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td><?php echo number_format($sub, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form method="post" action="pruebas_guardar.php" class="form">
            <label>Nombre del cliente</label>
            <input type="text" name="nombre_cliente" required>
            <button type="submit">Confirmar venta de prueba</button>
        </form>
    <?php endif; ?>
</section>
<?php include 'footer.php'; ?>
