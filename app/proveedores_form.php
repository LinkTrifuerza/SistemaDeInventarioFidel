<?php
require 'auth_middleware.php';
require_role('ADMINISTRADOR');
require 'db.php';
include 'header.php';

$id = $_GET['id'] ?? null;
$proveedor = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
    $stmt->execute([$id]);
    $proveedor = $stmt->fetch();
}
?>
<h1><?php echo $id ? 'Editar proveedor' : 'Nuevo proveedor'; ?></h1>

<form method="post" action="proveedores_save.php" class="form">
    <input type="hidden" name="id" value="<?php echo $proveedor['id_proveedor'] ?? ''; ?>">

    <label>Nombre</label>
    <input type="text" name="nombre" required value="<?php echo htmlspecialchars($proveedor['nombre'] ?? ''); ?>">

    <label>Empresa</label>
    <input type="text" name="empresa" value="<?php echo htmlspecialchars($proveedor['empresa'] ?? ''); ?>">

    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?php echo htmlspecialchars($proveedor['telefono'] ?? ''); ?>">

    <label>Correo</label>
    <input type="email" name="correo" value="<?php echo htmlspecialchars($proveedor['correo'] ?? ''); ?>">

    <label>Dirección</label>
    <input type="text" name="direccion" value="<?php echo htmlspecialchars($proveedor['direccion'] ?? ''); ?>">

    <label>Categoría</label>
    <input type="text" name="categoria" value="<?php echo htmlspecialchars($proveedor['categoria'] ?? ''); ?>">

    <label>Estatus</label>
    <select name="estatus">
        <option value="ACTIVO" <?php echo (($proveedor['estatus'] ?? '') === 'ACTIVO') ? 'selected' : ''; ?>>ACTIVO</option>
        <option value="INACTIVO" <?php echo (($proveedor['estatus'] ?? '') === 'INACTIVO') ? 'selected' : ''; ?>>INACTIVO</option>
    </select>

    <button type="submit">Guardar</button>
    <a class="btn" href="proveedores_list.php">Cancelar</a>
</form>
<?php include 'footer.php'; ?>
