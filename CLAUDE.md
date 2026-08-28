# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> El proyecto, sus comentarios y su UI están en español. Escribe código, commits y mensajes al usuario en español.

## Comandos

```bash
composer install                 # dependencias PHP (vendor/ no está en el repo)
npm install                      # dependencias front (node_modules/ no está en el repo)
php spark serve                  # servidor de desarrollo en http://localhost:8080
npm run dev                      # Tailwind en modo --watch (necesario al tocar clases en las vistas)
npm run build                    # Tailwind minificado para producción
php spark migrate                # aplica app/Database/Migrations
php spark db:seed InitialSeeder  # datos maestros (tipos, estados, prioridades, usuarios)
vendor/bin/phpunit               # toda la suite (alias: composer test)
vendor/bin/phpunit tests/unit/HealthTest.php                     # un fichero
vendor/bin/phpunit --filter testBaseUrlHasBeenSet                # un test
```

**En esta máquina no hay PHP ni Composer en el PATH** (sí Node/npm). Cualquier comando `php`/`composer` fallará hasta que se instale Laragon o XAMPP — ver [SETUP_GUIDE.md](SETUP_GUIDE.md). No asumas que puedes ejecutar la app o los tests para verificar un cambio.

## Arquitectura

CodeIgniter 4 (MVC clásico, sin módulos ni capa de servicios). Front renderizado en servidor con Tailwind v4 + PWA.

### Multi-tenancy por "escenarios" — el concepto central

Todo el sistema está segmentado por **escenarios** (agrupaciones lógicas de clientes). La tabla pivote `usuario_escenario (usuario_id, escenario_id, activo)` define qué ve cada usuario, y el propio usuario activa/desactiva escenarios desde `Profile::updateEscenarios`.

El patrón se repite literalmente en 6 ficheros: un método privado `getEscenariosActivos()` que lee `session()->get('id')`, consulta `usuario_escenario` y devuelve un array de IDs, seguido de `->whereIn('tickets.escenario_id', $escenariosActivos)`. Está duplicado en `TicketModel`, `ClienteModel`, `UsuarioModel`, `TicketRecurrenteModel`, `Tickets` y `Home`. **Cualquier consulta nueva de datos de negocio debe filtrar por escenarios activos o filtrará datos entre tenants.** Si el array viene vacío hay que devolver `[]` / `0`, nunca todos los registros.

Consecuencia importante: los modelos dependen de la sesión HTTP. Por eso [Api.php](app/Controllers/Api.php) **no** usa los métodos del modelo con filtro de escenario, sino queries propias con `findAll()` — la API es de nivel admin y lo ve todo.

### Autenticación y filtros

- Sesión clásica: `AuthController::login` verifica con `password_verify` y guarda `id`, `nombre`, `email`, `rol_id`, `imagen`, `isLoggedIn` en sesión.
- `App\Filters\AuthFilter` (alias `auth`) protege el grupo grande de rutas en [Routes.php](app/Config/Routes.php). El grupo `api` queda **fuera** de ese filtro.
- La API se autentica sola en `Api::initController` comparando la cabecera `X-API-KEY` con `getenv('SIMM_API_KEY')`; si la env no existe, devuelve 401 (fail-safe). Contrato completo en [API_GUIDE_FOR_AI.md](API_GUIDE_FOR_AI.md).
- **CSRF está desactivado** globalmente en [Filters.php](app/Config/Filters.php) (comentado en `$globals`). Los formularios POST no llevan token; si lo activas, hay que tocar todas las vistas.
- Roles por `rol_id`: 1 admin, 2 soporte, 3 cliente. Se comprueban con `==` a pelo en los controladores; no hay capa de permisos.

### Vistas: convención `_modern` y `$content`

`templates/layout_modern.php` es el layout vivo. No usa `$this->extend()/section()` de CI4: hace `include(APPPATH . "Views/{$content}.php")`, así que **los controladores pasan `'content' => 'tickets/index_modern'`** junto con `'title'` y devuelven `view('templates/layout_modern', $data)`.

Las vistas con sufijo `_modern` son las actuales. Las versiones sin sufijo (`tickets/index.php`, `clientes/index2.php`, `templates/layout.php`, `header.php`, `sidebar.php`, `footer2.php`…) son legado y en su mayoría ya no se enrutan — la excepción es `Notifications::index`, que aún usa `templates/layout`. Antes de modificar una pantalla, confirma qué vista carga realmente el controlador.

El layout incluye además: script anti-flash de tema oscuro (clase `.dark` en `<html>` + `localStorage`), el dropdown de notificaciones con polling `fetch('/notifications/list')` cada 60 s, y el arranque de push con `VAPID_PUBLIC_KEY` inyectada desde `.env`.

### Tailwind v4 (configuración en CSS, no en JS)

No existe `tailwind.config.js`. Todo se define en [public/css/src/tailwind-input.css](public/css/src/tailwind-input.css): `@theme` con los colores corporativos (`primary #137fec`, `background-light/dark`, `surface-light/dark`, `text-secondary`), `@variant dark` para activar dark mode por clase, y utilidades propias como `.form-input`. Los `@source` apuntan a `app/Views/**/*.php` y `public/js/**/*.js` — las clases que uses fuera de esas rutas no se generarán. El CSS compilado (`public/css/tailwind.css`) sí está versionado: regenéralo con `npm run build` antes de commitear cambios de estilo.

### Notificaciones y Web Push

`NotificationModel` tiene un callback `afterInsert` → `triggerPushAfterInsert` → `sendPushNotification`. Es decir: **basta con hacer `$notificationModel->insert([...])` para que salga el push**; no invoques WebPush manualmente. Lee las suscripciones de `push_subscriptions`, envía con `minishlink/web-push` usando las VAPID de `.env`, y borra las suscripciones caducadas (410 Gone). Los errores se tragan en un `try/catch` que sólo hace `log_message('error', ...)`.

Los tickets y movimientos crean notificaciones para `usuario_id` y `responsable_id`, excluyendo siempre al usuario que ejecuta la acción.

### Ficheros subidos

Van a `FCPATH . 'upload/'` (o sea `public/upload/`), no a `writable/`: `upload/tickets/{ticketId}/` para adjuntos y `upload/avatars/` para fotos de perfil. `public/upload/` está en `.gitignore`, así que en un clon nuevo el directorio no existe y las imágenes referenciadas en BD aparecerán rotas.

Ese directorio estuvo versionado por error (624 adjuntos reales de clientes, 196 MB) y se purgó del historial junto con las credenciales. **No lo vuelvas a añadir al repositorio**: son datos personales de terceros y el repositorio es público.

## Trampas conocidas

- **Nunca escribas credenciales en el código.** El repositorio es público y ya arrastró una fuga: contraseña de BD, `MIGRATION_TOKEN` y clave API estaban en `app/Config/Database.php`, en un duplicado huérfano en la raíz y en `.env.production` versionado. El 2026-08-28 se purgó el historial completo (`git filter-branch` + force-push a `main`, `develop` y `chore/retirar-deploy-ftp-y-uploads`) y todo pasó a `.env`. Cualquier secreto va al `.env`, nunca a un fichero versionado, y tampoco a los `.md` como "ejemplo".
- Para arrancar en local hay que copiar la plantilla [env](env) a `.env` y rellenarla: `app.baseURL`, `database.default.*`, `SIMM_API_KEY`, `VAPID_SUBJECT` / `VAPID_PUBLIC_KEY` / `VAPID_PRIVATE_KEY`, `MIGRATION_TOKEN`. Sin `.env` la conexión falla con "Access denied" y la API responde 401 a todo.
- **`public/assets/` está gitignoreado pero el layout lo referencia**: `assets/js/push-handler.js` y `assets/images/icon-192.png` no existen en el clon, así que el push nunca arranca (`PushHandler is undefined`) hasta que se regeneren esos ficheros.
- **`database/schema.sql` está incompleto**: no incluye `notifications` ni `push_subscriptions`, que sólo existen vía migraciones. Tras importar el schema hay que ejecutar `php spark migrate`.
- **IDs de estado hardcodeados** por todo el código: `3` = Cerrado, y `11` se excluye junto al 3 en los listados de abiertos/programados. Al tocar `estados_ticket` hay que respetar esos IDs.
- **Filtrado en PHP, no en SQL**: `Tickets::index` trae todos los tickets del escenario y luego aplica `applyAdvancedFilters()` con `array_filter` sobre el array. Funciona, pero no escala y rompe cualquier paginación.
- `TicketModel` usa subconsultas con SQL crudo multilínea dentro de `join()` para calcular el "último movimiento no automático". Si cambias `ticket_movimientos`, revisa los tres métodos afectados (`getTicketsWithClients`, `getTicketsClosedWithClients`, `getTicketsScheduledWithClients`).
- `clientes` tiene una restricción única sobre `nombre` llamada, por error histórico, `email_unique`. Se valida por software; no la interpretes como un índice sobre email.

## Convenciones de la casa

En `.agent/rules/` hay reglas de estilo que el proyecto ya sigue y conviene mantener:

- **Backend**: MVC estricto, PSR-12, lógica de datos en los modelos, Query Builder (nunca SQL concatenado con input), migraciones para cambios de esquema, evitar N+1.
- **Frontend**: estética moderna (sombras suaves, transiciones, micro-interacciones), variables CSS para el tema, Flexbox/Grid, todo responsive, estados hover explícitos.

En `.agents/skills/` hay skills de referencia (`ci4-best-practices`, `ci4-db-optimization`, `ci4-security`) con ejemplos aplicados a este código.
