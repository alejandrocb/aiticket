# Sistema de Gestión de Tickets - CodeIgniter 4

Este es un sistema de gestión de tickets desarrollado con CodeIgniter 4. Permite administrar clientes, usuarios, y el flujo de resolución de incidencias.

## Estructura de Base de Datos

El sistema se conecta a una base de datos MySQL (remota). Las principales tablas son:

- **tickets**: Núcleo del sistema. Almacena las incidencias, estado, prioridad y asignación.
- **clientes**: Información de las empresas o personas que reportan incidencias.
- **usuarios**: Agentes y administradores del sistema.
- **ticket_movimientos**: Historial de cambios y comentarios en cada ticket.
- **escenarios**: Entornos o agrupaciones lógicas de clientes.

*El esquema completo se encuentra en `database/schema.sql`.*

## Requisitos del Servidor

- PHP 7.4 o superior (Recomendado PHP 8.1+)
- Extensiones PHP: `intl`, `mbstring`, `json`, `mysqlnd`, `xml`.

## Instalación y Configuración

1.  **Clonar el repositorio**
    ```bash
    git clone <url-del-repo>
    cd aiticket
    ```

2.  **Instalar dependencias**
    ```bash
    composer install
    ```

3.  **Configurar Entorno**
    - Copiar el archivo `env` a `.env`
    - Configurar la URL base (`app.baseURL`)
    - Configurar la conexión a base de datos (`database.default`)

4.  **Ejecutar localmente**
    ```bash
    php spark serve
    ```

## Estructura del Proyecto

- `app/Controllers`: Lógica de negocio.
- `app/Models`: Interacción con la base de datos.
- `app/Views`: Interfaz de usuario (HTML/PHP).
- `public`: Directorio raíz web (CSS, JS, imágenes).

## Notas Importantes

> [!WARNING]
> **Base de Datos Remota**: El archivo de configuración de base de datos apunta a un host remoto. Asegúrate de tener permisos de acceso desde tu IP.

> [!NOTE]
> **Usuarios**: La tabla `usuarios` tiene una clave única en `email`.
> **Clientes**: La tabla `clientes` tiene una restricción única en `nombre` llamada `email_unique` (anomalía conocida, se está validando por software).
