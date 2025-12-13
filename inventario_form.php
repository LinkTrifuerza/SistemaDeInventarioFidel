<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';

$id = $_GET['id'] ?? null;
$producto = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
}

$proveedores = $pdo->query(
    "SELECT id_proveedor, nombre
     FROM proveedores
     WHERE estatus = 'ACTIVO'"
)->fetchAll();

include 'header.php';
?>
<h1><?php echo $id ? 'Editar producto' : 'Nuevo producto'; ?></h1>

<form method="post" action="inventario_save.php" class="form">
    <input type="hidden" name="id" value="<?php echo $producto['id_producto'] ?? ''; ?>">

    <label>Nombre</label>
    <input type="text" name="nombre" required value="<?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>">

    <label>Categoría</label>
    <input type="text" name="categoria" required value="<?php echo htmlspecialchars($producto['categoria'] ?? ''); ?>">

    <label>Descripción</label>
    <textarea name="descripcion"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>

    <label>Precio de venta</label>
    <input type="number" step="0.01" name="precio" required value="<?php echo $producto['precio'] ?? '0'; ?>">

    <label>Stock actual</label>
    <input type="number" name="stock_actual" required value="<?php echo $producto['stock_actual'] ?? '0'; ?>">

    <label>Stock mínimo</label>
    <input type="number" name="stock_minimo" required value="<?php echo $producto['stock_minimo'] ?? '0'; ?>">

    <label>Stock máximo</label>
    <input type="number" name="stock_maximo" required value="<?php echo $producto['stock_maximo'] ?? '0'; ?>">

    <?php if (!$id): ?>
        <label>Costo total inicial (compra)</label>
        <input type="number" step="0.01" name="costo_total_inicial" value="0">
        

        <label>Nombre de la empresa emisora (orden inicial)</label>
        <input type="text" name="empresa_emisora_inicial" value="Mi Empresa">
    <?php endif; ?>

    <hr>

    <h2>Proveedor</h2>

    <label>Proveedor existente</label>
    <select name="id_proveedor">
        <option value="">-- Ninguno / Registrar nuevo --</option>
        <?php foreach ($proveedores as $pr): ?>
            <option value="<?php echo $pr['id_proveedor']; ?>"
                <?php echo (isset($producto['id_proveedor']) && $producto['id_proveedor'] == $pr['id_proveedor']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($pr['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <h3>O registrar proveedor nuevo</h3>

    <label>Nombre proveedor</label>
    <input type="text" name="prov_nombre">

    <label>Empresa</label>
    <input type="text" name="prov_empresa">

    <label>Teléfono</label>
    <input type="text" name="prov_telefono">

    <label>Correo</label>
    <input type="email" name="prov_correo">

    <label>Dirección</label>
    <input type="text" name="prov_direccion">

    <label>Categoría proveedor</label>
    <input type="text" name="prov_categoria">

    <button type="submit">Guardar</button>
    <a class="btn" href="inventario_list.php">Cancelar</a>
</form>
<?php include 'footer.php'; ?>
