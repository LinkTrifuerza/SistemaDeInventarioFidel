<?php
require 'db.php';

$errores = [];
$exito = '';

// Clave de administradores
$CLAVE_ADMIN = '123456789';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = $_POST['nombre'] ?? '';
    $apellido  = $_POST['apellido'] ?? '';
    $usuario   = $_POST['usuario'] ?? '';
    $correo    = $_POST['correo'] ?? '';
    $telefono  = $_POST['telefono'] ?? '';
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $rol       = $_POST['rol'] ?? 'EMPLEADO';
    $clave_admin_ingresada = $_POST['clave_admin'] ?? '';

    // Validaciones básicas
    if ($password !== $password2) {
        $errores[] = 'Las contraseñas no coinciden';
    }

    if (!preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password)) {
        $errores[] = 'La contraseña debe tener mayúsculas, minúsculas y números';
    }

    // Usuario único
    $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM empleados WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $row = $stmt->fetch();
    if ($row && $row['c'] > 0) {
        $errores[] = 'El usuario ya existe';
    }

    
    if ($rol === 'ADMINISTRADOR') {
        if ($clave_admin_ingresada !== $CLAVE_ADMIN) {
            $errores[] = 'Clave de administrador incorrecta';
        }
    }

    if (empty($errores)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO empleados
             (nombre, apellido, usuario, password_hash, rol, correo, telefono, estatus, puesto, horario)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $nombre,
            $apellido,
            $usuario,
            $hash,
            $rol,
            $correo,
            $telefono,
            'ACTIVO',
            ($rol === 'ADMINISTRADOR' ? 'Administrador' : 'Empleado'),
            '9:00-18:00'
        ]);

        // Registro correcto: mensaje + redirección automática a login
        $exito = 'Usuario registrado correctamente. Serás redirigido a iniciar sesión.';
        header('Refresh: 3; URL=login.php'); // redirige en 3 segundos
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Inventario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-body">
<div class="login-box">
    <h1>Registro</h1>

    <?php foreach ($errores as $e): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($e); ?></div>
    <?php endforeach; ?>

    <?php if ($exito): ?>
        <div class="alert"><?php echo htmlspecialchars($exito); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Nombre</label>
        <input type="text" name="nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

        <label>Apellido</label>
        <input type="text" name="apellido" required value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>">

        <label>Usuario</label>
        <input type="text" name="usuario" required value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">

        <label>Correo</label>
        <input type="email" name="correo" required value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">

        <label>Teléfono</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">

        <label>Rol</label>
        <select name="rol" id="rol" onchange="toggleClaveAdmin()" required>
            <option value="EMPLEADO" <?php echo (($_POST['rol'] ?? '') === 'EMPLEADO') ? 'selected' : ''; ?>>EMPLEADO</option>
            <option value="ADMINISTRADOR" <?php echo (($_POST['rol'] ?? '') === 'ADMINISTRADOR') ? 'selected' : ''; ?>>ADMINISTRADOR</option>
        </select>

        <div id="campo-clave-admin" style="display: none; margin-top: 10px;">
            <label>Clave de administrador</label>
            <input type="password" name="clave_admin">
        </div>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Repetir contraseña</label>
        <input type="password" name="password2" required>

        <button type="submit">Registrarse</button>
    </form>

    <a class="btn btn-full" href="login.php">Volver a iniciar sesión</a>
</div>

<script>
function toggleClaveAdmin() {
    const rolSelect = document.getElementById('rol');
    const campoClave = document.getElementById('campo-clave-admin');
    if (rolSelect.value === 'ADMINISTRADOR') {
        campoClave.style.display = 'block';
    } else {
        campoClave.style.display = 'none';
    }
}

// Inicializar al cargar
toggleClaveAdmin();
</script>
</body>
</html>
