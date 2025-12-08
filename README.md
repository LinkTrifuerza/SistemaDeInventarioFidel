# SistemaDeInventarioFidel
Este proyecto fue realizado por:
-Bustillos Landa Guimel
-Cazares Rochin Ricardo
-Orduño Angulo Gustavo
-Ruiz Arias Omar
-Vega Garcia Susana Valentina

DESCRIPCION DEL PROYECTO: 
Es un sistema web de inventario desarrollado en PHP con MySQL, pensado para pequeñas empresas que necesitan controlar productos, proveedores y movimientos de stock. Permite registrar productos con stock mínimo y máximo, generar órdenes de compra a proveedores, registrar ventas de prueba y llevar un historial de entradas y salidas de inventario.
Incluye gestión de empleados con roles (administrador y empleado), autenticación con login, notificaciones automáticas cuando el stock llega a niveles críticos y reportes básicos como tickets de órdenes y tickets de ventas de prueba.

TIPO DE ARQUITECTURA UTILIZADA EN EL PROYETO:
Es una arquitectura monolítica php por módulos con capas básicas.
Tipo de arquitectura
-Monolito web:
Todo el sistema vive en una misma aplicación PHP dentro de XAMPP.
-Patrón por páginas:
Cada archivo .php actúa como controlador de una funcionalidad especifica.
-Separación ligera en capas:
*Presentación: HTML y CSS en los mismos .php
*Lógica de aplicación: código PHP en cada página que valida roles, procesa
formularios y ejecuta lógica de negocio.
*Acceso a datos: db.php centraliza la conexión.
Organización por módulos
*Módulos funcionales: inventario, proveedores, empleados, tickets, pruebas, perfil,
cada uno con sus páginas CRUD y vistas.
*Reutilización básica: archivos compartidos (db.php, auth_middleware.php,
stock_notificaciones.php, header.php, navbar.php, footer.php) usados en todo el
sistema.


