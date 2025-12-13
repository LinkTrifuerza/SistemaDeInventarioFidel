<?php
function insertar_notificacion(PDO $pdo, int $id_producto, string $tipo, string $mensaje, ?int $id_empleado_destino = null): void {
    $stmt = $pdo->prepare(
        "INSERT INTO notificaciones
         (id_empleado_destino, tipo, mensaje, id_producto)
         VALUES (?,?,?,?)"
    );
    $stmt->execute([
        $id_empleado_destino,
        $tipo,
        $mensaje,
        $id_producto
    ]);
}

function verificar_stock_y_notificar(PDO $pdo, int $id_producto): void {
    $stmt = $pdo->prepare(
        "SELECT id_producto, nombre, stock_actual, stock_minimo, stock_maximo
         FROM productos
         WHERE id_producto = ?"
    );
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch();
    if (!$producto) {
        return;
    }

    // Notificar solo cuando stock_actual sea EXACTAMENTE igual al mínimo
    if ($producto['stock_minimo'] > 0 &&
        (int)$producto['stock_actual'] === (int)$producto['stock_minimo']) {

        $mensaje = 'Stock mínimo alcanzado para ' . $producto['nombre'];
        insertar_notificacion($pdo, $producto['id_producto'], 'STOCK_MINIMO', $mensaje, null);
    }

    // Stock máximo (puedes dejar igual o también hacerlo exacto si quieres)
    if ($producto['stock_maximo'] > 0 &&
        $producto['stock_actual'] >= $producto['stock_maximo']) {

        $mensaje = 'Stock máximo alcanzado para ' . $producto['nombre'];
        insertar_notificacion($pdo, $producto['id_producto'], 'STOCK_MAXIMO', $mensaje, null);
    }
}

function obtener_notificaciones_no_leidas(PDO $pdo, ?int $id_empleado): array {
    if ($id_empleado) {
        $stmt = $pdo->prepare(
            "SELECT * FROM notificaciones
             WHERE (id_empleado_destino IS NULL OR id_empleado_destino = ?)
             AND leida = 0
             ORDER BY fecha_creacion DESC
             LIMIT 10"
        );
        $stmt->execute([$id_empleado]);
    } else {
        $stmt = $pdo->query(
            "SELECT * FROM notificaciones
             WHERE leida = 0
             ORDER BY fecha_creacion DESC
             LIMIT 10"
        );
    }
    return $stmt->fetchAll();
}
