<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing


Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

# EN ESTE PROYECTO DEL EXAMEN 2 SE ESTABLECE LOS REQUERIMIENTOS FUNCIONALES DEL SISTEMA Y NO FUNCIONALES DEPEDIENDO EN EL CASO 
#Desarrollar una aplicación web para la gestión de renta de películas, basada en un escenario realista con tres  tipos de usuarios: clientes, empleados de sucursal y un administrador general. La plataforma permitirá realizar  operaciones como autenticación por roles, gestión de rentas y devoluciones, administración de catálogos de  películas, consultas de disponibilidad en inventario, generación de reportes y consumo de una API externa para  el registro automatizado de títulos. 
Objetivo:  
Diseñar, desarrollar y desplegar una aplicación web funcional para la administración de un sistema de renta de  películas, aplicando de forma integral los conocimientos de programación, bases de datos, desarrollo web. 
#1. Requerimientos funcionales 
A) Empleado por sucursal 
1. Autenticación de empleados 
o El empleado debe iniciar sesión con credenciales propias (usuario y contraseña). o La aplicación debe restringir el acceso y las operaciones a la sucursal a la que pertenece. o Registrar la última fecha y hora de acceso para auditoría. 
2. Gestión de alquileres (rentas) 
o Registrar nuevas rentas de películas a clientes. 
o Validar disponibilidad en inventario de la sucursal antes de registrar la renta. 
o Registrar devoluciones de películas. 
o Calcular y mostrar cargos por retraso en tiempo real. 
o Bloquear automáticamente la renta si el cliente tiene cargos vencidos. 
3. Gestión de clientes 
o Registrar nuevos clientes y actualizar su información (nombre, dirección, teléfono, email). o Consultar historial de rentas de un cliente en esa sucursal. 
o Validar duplicidad de clientes mediante email o CURP. 
4. Inventario de la sucursal 
o Consultar películas disponibles (por título, categoría, actor, idioma). 
o Marcar copias de películas como perdidas o dañadas, actualizando el inventario. o Ver histórico de movimientos de cada copia (rentas, daños, devoluciones). 
B) Cliente 
1. Autenticación de clientes 
o Registro e inicio de sesión con validación de datos de contacto y dirección. 
o Recuperación de contraseña mediante correo electrónico. 
o Validación de duplicidad por email. 
2. Consulta de catálogo 
o Buscar películas disponibles en una tienda (por título, categoría, actor, idioma). 
o Filtrar películas por disponibilidad inmediata. 
o Ver detalles completos: sinopsis, actores, idioma, categoría, duración, precio de renta y  disponibilidad por sucursal. 
3. Gestión de cuenta
o Consultar historial de rentas. 
o Consultar pagos realizados y cargos pendientes. 
o Recibir alertas “notificaciones push” de próximas fechas de devolución o retrasos. C) Administrador general 
1. Gestión global 
o Administrar información de tiendas, empleados y clientes. 
o Dar de alta, baja o modificar empleados y asignarlos a sucursales. 
o Resetear contraseñas y bloquear cuentas en caso necesario. 
2. Reportes y estadísticas 
o Ver estadísticas de rentas por sucursal, categoría o actor. 
o Generar reportes de ingresos por tienda y globales. 
o Consultar las películas más rentadas (ranking). 
o Consultar clientes con mayor número de rentas. 
o Exportar reportes en formatos CSV y PDF. 
3. Mantenimiento de catálogo 
o Dar de alta nuevas películas, categorías e idiomas. 
o Actualizar información de películas existentes. 
o Administrar inventario global (copias en tiendas). 
o Integrar con la API de OMDb http://www.omdbapi.com/ “consumo de una API externa para el  registro automatizado de títulos” 
2. Requerimientos no funcionales 
1. Seguridad 
o Autenticación basada en roles (Empleado, Cliente, Administrador). 
o Restricción de acceso a información por rol y sucursal. 
o Cifrado de contraseñas (bcrypt u otro algoritmo seguro). 
o Validación de entradas para evitar inyecciones SQL y XSS. 
o Registro de logs de actividad relevantes (rentas, accesos, cambios de inventario). 2. Usabilidad 
o Interfaz web responsiva usando un framework de diseño moderno (Bootstrap, Tailwind u otro). o Paneles de control separados y personalizados por rol. 
o API pública para consulta de películas, inventarios y rankings. 
o Navegación clara e intuitiva. 
3. Disponibilidad y rendimiento 
o Manejo optimo de recursos (evitar consultas lentas/procesos innecesarios) 
o Manejo de concurrencia en rentas (evitar doble reserva). 
o Paginación en listados extensos para mejorar rendimiento. 
4. Mantenibilidad y buenas prácticas 
o Uso de control de versiones con Git: 
▪ Flujo de trabajo basado en ramas (main, develop, feature/*, hotfix/*). 
▪ Commits descriptivos y detallados 
o Estructura modular y separación por capas (MVC u otra arquitectura clara). 
o Uso de patrones de diseño cuando aplique (Strategy, Repository, etc.). 
o Uso de un ORM para manejo de la base de datos. 
o Documentación clara del código.
5. Pruebas y aseguramiento de calidad 
o Pruebas unitarias para la lógica de negocio crítica. 
o Pruebas funcionales básicas para flujos principales (registro, renta, devolución). 6. Integración Continua 
o Repositorio en GitHub/GitLab. 
o Configuración de entornos (desarrollo / producción). 
o Backup periódico de la base de datos. 
o Monitoreo básico de logs y disponibilidad. 
#Usuarios del sistema 
• Cliente → Consulta catálogo y gestiona sus rentas. 
• Empleado de sucursal → Gestiona clientes, inventario y rentas. 
• Administrador general → Administra tiendas, empleados, catálogo y reportes. • Público general → Acceso limitado a la API pública para consultas de películas. 

# EN LA CUAL EN ESTE PROYECTO DEBEMOS SIEMPRE DE CUIDAR LA SEGURIDAD DEL DE LAS VUNERABILIDADES EN LAS CUALES AL MOMENTO DE QUE SE HACE UN SISTEMA DE GESTIÓN SE IDENTIFICA LOS ERRORES 
# QUE TIENE UN SISTEMA DE GESTIÓN DE PELICULAS DEBE DE TENER EL ACCESO A LAS CONFIGURACIONES DE LOS ROLES EN LOS DATOS Y LA MAYOR DE LA SEGURIDAD EN LA GENERACIÓN DE LAS APIS EXTERNAS 
# DENTRO DE LA APLICACIÓN EN LA CUAL ES LA PARTE DE LA DOCUMENTACIÓN EN LARAVEL PERO SIEMPRE DEBEMOS DE INVESTIGAR ANTES DE IMPLEMEMTAR QUE HACE CADA RUTA CARPETA O ARCHIVO 
# EN LA LÓGICA QUE TIENE LARAVEL Y HACER RESPALDOS DE TUS PROYECTOS.



The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
