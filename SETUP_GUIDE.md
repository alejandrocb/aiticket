# Guía de Configuración de Entorno Local (Windows)

Para ejecutar este proyecto en tu ordenador, necesitas un servidor web (Apache/Nginx), PHP y una base de datos (MySQL/MariaDB).

Recomendamos **Laragon** por ser la opción más moderna, rápida y fácil de usar en Windows, aunque **XAMPP** también es válido.

## Opción A: Usando Laragon (Recomendada)

1.  **Descargar e Instalar**:
    - Ve a [laragon.org/download](https://laragon.org/download/) y descarga la versión **Full**.
    - Instálalo (siguiente, siguiente...).

2.  **Iniciar Servicios**:
    - Abre Laragon y pulsa el botón **"Iniciar Todo"**.
    - Verás que Apache y MySQL (MariaDB) se inician.

3.  **Configurar Base de Datos**:
    - Haz clic en el botón **"Base de Datos"** en Laragon (abrirá HeidiSQL, que viene incluido).
    - Crea una nueva sesión (usuario `root`, contraseña vacía por defecto).
    - Haz clic derecho en la lista de bases de datos -> Crear nuevo -> Base de datos. Llámala `aiticket` (o el nombre que prefieras).
    - Selecciona la base de datos `aiticket`, ve a "Archivo" -> "Cargar archivo SQL" y selecciona el archivo `database/schema.sql` de este proyecto.

4.  **Configurar Proyecto**:
    - Abre el archivo `.env` en tu proyecto.
    - Cambia `database.default.hostname` a `localhost`.
    - Cambia `database.default.database` a `aiticket`.
    - Cambia `database.default.username` a `root`.
    - Cambia `database.default.password` a `` (vacío) o la que hayas puesto.

5.  **Ejecutar**:
    - Puedes usar la terminal en la carpeta del proyecto: `php spark serve`.
    - O simplemente acceder via navegador si configuras Laragon para que apunte a la carpeta `public`.

## Opción B: Usando XAMPP

1.  **Descargar**: [apachefriends.org](https://www.apachefriends.org/es/index.html).
2.  **Instalar**: Asegúrate de marcar PHP y MySQL.
3.  **Iniciar**: Abre el Panel de Control de XAMPP y arranca Apache y MySQL.
4.  **Base de Datos**:
    - Ve a `http://localhost/phpmyadmin`.
    - Crea una base de datos llamada `aiticket`.
    - Pestaña "Importar" -> Selecciona `database/schema.sql`.
5.  **Configurar**: (Igual que en Laragon, editar `.env`).

---

> [!TIP]
> Si ya tienes Composer instalado, ejecuta `composer install` primero para bajar las dependencias de CodeIgniter.
