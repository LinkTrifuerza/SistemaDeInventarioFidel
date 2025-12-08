# SistemaDeInventarioFidel

## ESTE PROYECTO FUE REALIZADO POR:
- Bustillos Landa Guimel  
- Cazares Rochin Ricardo  
- Orduño Angulo Gustavo  
- Ruiz Arias Omar  
- Vega Garcia Susana Valentina  

## DESCRIPCIÓN DEL PROYECTO
Es un sistema web de inventario desarrollado en PHP con MySQL, pensado para pequeñas empresas que necesitan controlar productos, proveedores y movimientos de stock.  
Permite registrar productos con stock mínimo y máximo, generar órdenes de compra a proveedores, registrar ventas de prueba y llevar un historial de entradas y salidas de inventario.  
Incluye gestión de empleados con roles (administrador y empleado), autenticación con login, notificaciones automáticas cuando el stock llega a niveles críticos y reportes básicos como tickets de órdenes y tickets de ventas de prueba.

## ARQUITECTURA UTILIZADA
Es una arquitectura monolítica PHP por módulos con capas básicas.

### TIPO DE ARQUITECTURA
- **Monolito web:** Todo el sistema vive en una misma aplicación PHP dentro de XAMPP.  
- **Patrón por páginas:** Cada archivo `.php` actúa como controlador de una funcionalidad específica.  

### CAPAS
- **Presentación:** HTML y CSS en los mismos `.php`.  
- **Lógica de aplicación:** Código PHP que valida roles, procesa formularios y ejecuta la lógica de negocio.  
- **Acceso a datos:** `db.php` centraliza la conexión.

### ORGANIZACIÓN POR MÓDULOS
- Módulos funcionales: inventario, proveedores, empleados, tickets, pruebas, perfil, cada uno con sus páginas CRUD y vistas.  
- Reutilización básica: archivos compartidos (`db.php`, `auth_middleware.php`, `stock_notificaciones.php`, `header.php`, `navbar.php`, `footer.php`) usados en todo el sistema.

## SCRIPT DE LA BASE DE DATOS

El script SQL para crear la base de datos está en:

- [`inventario_sistema.sql`](DB/inventario_sistema.sql)

## DIAGRAMAS DEL SISTEMA

### Diagrama Entidad–Relación

![Diagrama Entidad Relación](PNG/DIAGRAMA%20ENTIDAD%20RELACION.png)

### Diagrama de Casos de Uso

![Casos de uso](PNG/CASOS%20DE%20USO.png)
