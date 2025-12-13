# SistemaDeInventarioFidel

## ESTE PROYECTO FUE REALIZADO POR:
- Bustillos Landa Guimel  
- Cazares Rochin Ricardo  
- Orduño Angulo Gustavo  
- Ruiz Arias Omar  
- Vega Garcia Susana Valentina  

## 1. INTRODUCCIÓN
El sistema es una aplicación web diseñada para facilitar el control de productos y existencias en pequeñas y medianas empresas mediante una solución sencilla y centralizada. Desarrollado en PHP con MySQL y ejecutado sobre XAMPP, el sistema integra en una sola plataforma la gestión de empleados, proveedores, productos, órdenes de compra y ventas de prueba, permitiendo mantener el inventario actualizado, trazable y respaldado con tickets y movimientos de stock.

## 2. RESUMEN DEL SISTEMA
Es un sistema web de gestión de inventario desarrollado en PHP con MySQL que permite a pequeñas empresas controlar sus existencias, proveedores y empleados desde un solo lugar. Incluye módulos para registrar y editar productos, definir niveles de stock mínimo y máximo, generar órdenes de compra a proveedores, registrar ventas de prueba y actualizar automáticamente las entradas y salidas de inventario.​

El sistema maneja usuarios con roles de administrador y empleado, incorpora autenticación con login, notificaciones automáticas cuando el stock llega a niveles críticos y mantiene un historial de movimientos de stock y tickets de órdenes y ventas de prueba para consulta y seguimiento.

## 3. REQUISITOS
### a.Requisitos funcionales

1. El sistema permite el **inicio de sesión de usuarios** mediante nombre de usuario y contraseña, diferenciando entre roles de **administrador** y **empleado**.  
2. El sistema permite al administrador **gestionar empleados**: registrar, editar y cambiar el estatus (**ACTIVO / INACTIVO**).  
3. El sistema permite **gestionar proveedores**: registrar, editar, cambiar estatus y evitar nuevos pedidos a proveedores inactivos.  
4. El sistema permite **registrar productos** asociados a un proveedor, incluyendo **precio de venta**, **stock actual**, **stock mínimo** y **stock máximo**.  
5. El sistema permite **editar e inactivar productos**, evitando su uso en ventas y órdenes cuando estén inactivos.  
6. El sistema permite **generar órdenes de compra** a proveedores con detalle de productos, cantidades, costos unitarios y monto total.  
7. El sistema **registra órdenes iniciales de stock** cuando se crea un producto con existencias iniciales.  
8. El sistema **actualiza automáticamente el stock** de productos al registrar órdenes de compra (entradas) y ventas de prueba (salidas).  
9. El sistema permite **registrar ventas de prueba** con detalle de productos, cantidades, precio unitario y total de la venta.  
10. El sistema **genera y muestra tickets** de órdenes a proveedores y tickets de ventas de prueba para consulta posterior.  
11. El sistema mantiene un **historial de movimientos de stock** por producto, indicando tipo de movimiento, cantidad, motivo y referencia.  
12. El sistema genera **notificaciones de stock** cuando el stock de un producto es menor o igual al mínimo o mayor o igual al máximo configurado.  
13. El sistema permite **consultar el inventario** con filtros por nombre, categoría, proveedor y estatus del producto.  
14. El sistema permite al usuario autenticado **consultar y actualizar su propio perfil**, incluyendo cambio de contraseña.  

### b.Requisitos no funcionales

1. El sistema está implementado como una **aplicación web monolítica** en **PHP** con base de datos **MySQL**, ejecutándose sobre **XAMPP** en entorno local.  
2. El sistema responde a las operaciones típicas de consulta (listar productos, proveedores, tickets) en un **tiempo adecuado** para una base de datos de tamaño pequeño–medio.  
3. El sistema **requiere autenticación** para acceder a cualquier funcionalidad distinta al formulario de inicio de sesión.  
4. El sistema **restringe el acceso** a funcionalidades administrativas (gestión de empleados, proveedores, productos, órdenes) exclusivamente a usuarios con rol **administrador**.  
5. El sistema mantiene la **integridad referencial** de la base de datos mediante claves foráneas, impidiendo eliminar entidades que tengan registros relacionados.  
6. El sistema es accesible desde **navegadores web modernos** y ofrece una **interfaz sencilla**, basada en menús y tablas, comprensible para usuarios no técnicos.  
7. El sistema **almacena las contraseñas** de usuarios utilizando funciones de **hash** seguras provistas por PHP, evitando guardar claves en texto plano.  
8. El sistema permite realizar **copias de seguridad y restauración** de la información mediante exportación e importación de la base de datos en formato **SQL**.  

## 4. CASOS DE USO
### a. Diagrama
![Casos de uso](PNG/CASOS%20DE%20USO.png)
### b. Descripción 
falta
## 5. ENTIDADES, ATRIBUTOS Y RELACIONES INCLUYENDO CARDINALIDAD, DIAGRAMA ENTIDAD RELACIÓN

![Diagrama Entidad Relación](PNG/DIAGRAMA%20ENTIDAD%20RELACION.png)

## 6. ARQUITECTURA DEL SISTEMA
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
## 7. DISEÑO DE LA INTERFAZ (FIGMA)

[![Prototipo en Figma](PNG/figma-prototipo.png)]
(https://www.figma.com/design/tV3GLAKigTK7OUxLz2nHAS/Login-fidel?node-id=0-1&t=qbs3Jjmn3ZzE1pmE-1)
## 8. ESTRUCTURA DEL PROYECTO
## 9. INSTALACIÓN Y CONFIGURACIÓN
## 10. USO Y OPERACIÓN DEL SISTEMA
## 11. BASE DE DATOS

El script SQL para crear la base de datos está en:

- [`inventario_sistema.sql`](DB/inventario_sistema.sql)

## 12. CONCLUSION ACERCA DEL TRABAJO

Desarrollar este sistema de inventario fue una experiencia en la que se pusieron en práctica muchos conceptos vistos en clase y que permitió afianzarlos en un proyecto real, a lo largo del desarrollo se aprendió a diseñar y normalizar una base de datos, a estructurar una aplicación web en PHP por módulos, a manejar correctamente claves foráneas e integridad referencial, y a trabajar con control de versiones usando Git y GitHub, lo que acercó el proyecto a una forma de trabajo más profesional.​​

Además, el proceso hizo evidente la importancia de pensar en la experiencia del usuario y en los pequeños detalles: validaciones, mensajes claros, flujos coherentes para altas, ediciones e inactivaciones, y la necesidad de mantener un equilibrio entre funcionalidad y simplicidad. En general, el proyecto resultó retador pero satisfactorio, dejando la sensación de haber construido una herramienta útil y al mismo tiempo haber ganado confianza para abordar sistemas más complejos en el futuro.

