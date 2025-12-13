<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';
include 'header.php';

$id_proveedor_sel = $_GET['id_proveedor'] ?? '';

// Proveedores activos
$proveedores = $pdo->query(
    "SELECT id_proveedor, nombre
     FROM proveedores
     WHERE estatus='ACTIVO'
     ORDER BY nombre"
)->fetchAll();

// Productos solo del proveedor seleccionado (si hay uno)
$productos = [];
if ($id_proveedor_sel !== '') {
    $stmtProd = $pdo->prepare(
        "SELECT id_producto, nombre, precio
         FROM productos
         WHERE estatus = 'ACTIVO'
           AND id_proveedor = ?
         ORDER BY nombre"
    );
    $stmtProd->execute([$id_proveedor_sel]);
    $productos = $stmtProd->fetchAll();
}
?>
<h1>Nueva orden a proveedor</h1>

<form method="get" class="form">
    <label>Proveedor</label>
    <select name="id_proveedor" onchange="this.form.submit()" required>
        <option value="">Seleccione...</option>
        <?php foreach ($proveedores as $pr): ?>
            <option value="<?php echo $pr['id_proveedor']; ?>"
                <?php echo ($id_proveedor_sel && $id_proveedor_sel == $pr['id_proveedor']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($pr['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <!-- Este formulario GET solo sirve para elegir proveedor y recargar productos -->
</form>

<?php if ($id_proveedor_sel === ''): ?>
    <p>Selecciona un proveedor para ver sus productos.</p>
<?php else: ?>
    <form method="post" action="orden_proveedor_guardar.php" class="form">
        <input type="hidden" name="id_proveedor" value="<?php echo htmlspecialchars($id_proveedor_sel); ?>">

        <label>Nombre de la empresa emisora</label>
        <input type="text" name="nombre_empresa_emisora" required value="Mi Empresa">

        <h2>Productos a ordenar</h2>
        <p>Solo se muestran productos del proveedor seleccionado.</p>

        <div id="lineas-productos">
            <div class="linea-producto">
                <label>Producto</label>
                <select name="id_producto[]" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?php echo $p['id_producto']; ?>">
                            <?php echo htmlspecialchars($p['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Cantidad</label>
                <input type="number" name="cantidad[]" min="1" required>

                <label>Costo unitario</label>
                <input type="number" step="0.01" name="costo[]" min="0" required>
            </div>
        </div>

        <button type="button" class="btn" onclick="agregarLinea()">Agregar otro producto</button>
        <br><br>

        <button type="submit">Guardar orden</button>
        <a class="btn" href="proveedores_list.php">Cancelar</a>
    </form>

    <script>
    function agregarLinea() {
        const contenedor = document.getElementById('lineas-productos');
        const linea = document.querySelector('.linea-producto');
        const clon = linea.cloneNode(true);
        clon.querySelectorAll('input').forEach(input => input.value = '');
        clon.querySelector('select').selectedIndex = 0;
        contenedor.appendChild(clon);
    }
    </script>
<?php endif; ?>

<?php include 'footer.php'; ?>
