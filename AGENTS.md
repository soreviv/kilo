# Instrucciones para Agentes de IA (OtorrinoNet)

Este archivo proporciona contexto e instrucciones para que los asistentes de IA puedan entender y trabajar en este proyecto de manera efectiva.

## 1. Consejos para el Entorno de Desarrollo

Este proyecto utiliza un stack de PHP (framework MVC personalizado), MySQL, Nginx y TailwindCSS.

* **Instalar Dependencias:** Usa `composer install` para instalar las dependencias de PHP definidas en `composer.json`.
* **Variables de Entorno:** Copia `otorrinonet.com/.env.example` a `otorrinonet.com/.env`. Edita el archivo `.env` para configurar los detalles de tu base de datos local (host, nombre, usuario, contraseña).
* **Base de Datos:** La estructura de la base de datos se encuentra en `otorrinonet.com/database_schema.sql`. Debes importar este archivo manualmente en tu servidor MySQL.
* **Servidor Web:** El proyecto está diseñado para Nginx + PHP-FPM. Puedes encontrar una configuración de ejemplo en `configuracion_nginx_php_fpm.md`. La raíz pública (document root) debe apuntar a `otorrinonet.com/public/`.
* **Assets (CSS/JS):** Los estilos se definen con TailwindCSS. La configuración se encuentra en `otorrinonet.com/tailwind.config.js`. Probablemente necesites `npm install` y un script de compilación (ej. `npm run build`) para generar el CSS final en `public/assets/css/css/styles.css`.

## 2. Arquitectura Clave del Proyecto

* **Punto de Entrada:** Todo el tráfico es dirigido a `public/index.php`.
* **Enrutador:** Las rutas se definen en `app/routes.php` y son procesadas por `app/core/Router.php`.
* **Controladores:** La lógica de negocio está en `app/controllers/`.
    * `BaseController.php`: Contiene la lógica para renderizar vistas.
    * `AdminController.php`: Maneja el dashboard de administración.
    * `AuthController.php`: Maneja el inicio y cierre de sesión del administrador.
* **Modelos:** La lógica de base de datos está en `app/models/`.
    * `Database.php` (en `app/core`): Gestiona la conexión PDO.
    * `db_config.php` (en `app/config`): Provee las credenciales (leídas desde el `.env`).
* **Vistas:** Son archivos PHP puros ubicados en `app/views/`.

## 3. Instrucciones de Prueba

* **Pruebas Automatizadas:** No se detecta un framework de pruebas automatizadas (como PHPUnit o Pest) en el proyecto.
* **Pruebas Manuales:**
    1.  Inicia tu servidor web local (Nginx/PHP-FPM).
    2.  Verifica que las páginas públicas (Inicio, Servicios, Contacto) carguen correctamente.
    3.  Envía un formulario de "Agendar Cita" y verifica que los datos lleguen a la base de datos (tabla `appointments`).
    4.  Envía un formulario de "Contacto" y verifica (tabla `contact_messages`).
    5.  Accede a `/admin` e inicia sesión.
    6.  Verifica que el dashboard (`/admin/dashboard`) cargue y muestre las citas y mensajes.

## 4. Instrucciones para Pull Requests (PRs)

* **Linting:** No se detecta un linter de código (como PHP-CS-Fixer o Pint). Por favor, intenta mantener el estilo de código existente en los archivos que modifiques.
* **Formato de Título:** Usa el formato `[Sección] <Descripción del cambio>`.
    * Ejemplo: `[Admin] Arregla paginación en la lista de citas`
    * Ejemplo: `[Vista] Actualiza formulario de contacto`

## 5. Contexto de Actores del Sistema

(Esta sección describe los roles humanos y bots que interactúan con la aplicación).

### Visitante (Paciente)

Cualquier usuario público no autenticado.
* **Acciones:** Ver páginas (Inicio, Servicios, Legales), enviar formulario de Cita, enviar formulario de Contacto.
* **Controladores:** `HomeController`, `ServicesController`, `LegalController`, `AppointmentController`, `ContactController`.

### Usuario Administrador (Autenticado)

Un usuario que ha iniciado sesión en el panel `/admin`.
* **Roles (de BD):** `admin`, `recepcionista`, `doctor`.
* **Acciones:** Iniciar/Cerrar sesión, ver Dashboard, ver lista de Citas, ver lista de Mensajes, actualizar estado de Citas/Mensajes.
* **Controladores:** `AuthController`, `AdminController`.

### Agentes Automatizados (Bots y Rastreadores)

Definidos en `public/robots.txt`.
* Se permite el rastreo de todo el sitio (`Allow: /`) excepto `/admin/`, `/app/` y `/vendor/`.
* El mapa del sitio se encuentra en `public/sitemap.xml`.
