#Kids-r-us

####Proyecto de examen para la empresa bb.digital.

##Requisitos
 - PHP 8.0.2 o superior
 - Extensión `gd` para algunos de los tests
 - Composer 2.1.6 o superior

##Descripción
El proyecto consiste en un servicio web (web api) para un pequeño e-commerce 
de venta de artículos de niños. Consta de endpoints para registrarse e iniciar sesión 
en la plataforma y administrar la cuenta de usuario. Crear, actualizar, borrar etc. 
productos de venta, así como mostrar y vender dichos productos, como es obvio. Contiene 
endpoints para crear, actualizar y borrar reseñas hacia los productos. La documentación 
de todos los endpoints está en <http://localhost:8000/api/documentation>, teniendo en 
cuenta de que se esté usando el servidor local de desarrollo. Las pruebas de todos los 
controladores están en la carpeta *tests/Feature*. El almacenamiento usado es una base 
de datos sqlite integrada en la carpeta *database/database.sqlite*. Tiene un sistema de 
acceso a los endpoints basado en roles y permisos en el cual existen dos roles: 
administradores y editores. Los administradores tienen permiso para realizar cualquier 
operación. Los editores pueden realizar operaciones de inserción, actualización y 
eliminación de los productos, pero no pueden administrar las cuentas de usuarios ni 
los roles y permisos. Las rutas de los endpoints, aparte de estar en la documentación 
se encuentran en el script *routes/api.php*. Todas las rutas deben tener el prefijo */api*.

##Instrucciones de Instalación y prueba
Abra una consola de cmd en la carpeta del proyecto y ejecute los siguientes comandos.

 - `composer install` para instalar las dependencias del proyecto.
 - `php artisan serve` para ejecutar el servidor local de desarrollo.

Puede limpiar y volver a sembrar la base de datos con el siguiente comando
`php artisan migrate:fresh --seed`

Para acceder a los endpoints que requieren autenticación debe registrarse o iniciar sesión 
con una cuenta existente. La respuesta contendrá un token de acceso que debe agregar 
en un encabezado de `Authorization`. La primera cuenta en ser creada se le asignará el rol de 
administrador automáticamente para que pueda ser usada para asignar a otras cuentas como 
administradores y editores.
