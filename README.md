# Portal de Servicio Social ITSCO

Plataforma web para gestionar el proceso de Servicio Social del Instituto Tecnológico Superior de Cintalapa (ITSCO): captura y aprobación de perfiles de alumnos, entrega y revisión de documentos por periodo, y notificaciones (in-app y push del navegador) para mantener al estudiante al tanto del estatus de su trámite sin tener que estar consultando el sitio.

## Índice

- [Características](#características)
- [Stack tecnológico](#stack-tecnológico)
- [Requisitos](#requisitos)
- [Instalación local](#instalación-local)
- [Variables de entorno](#variables-de-entorno)
- [Scripts disponibles](#scripts-disponibles)
- [Testing](#testing)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Notificaciones push](#notificaciones-push)
- [Despliegue](#despliegue)

## Características

**Panel de administración** (rol `admin`)
- Gestión de periodos académicos, campus, carreras y semestres (catálogos).
- Aprobación/rechazo de perfiles de alumnos, con cola de revisión rápida ("Revisar pendientes").
- Gestión de documentos requeridos por periodo (plantillas, modos de carga: solo alumno, solo admin, bidireccional; individuales o generales).
- Visor unificado de revisión de documentos (PDF/Word en el navegador, sin descarga) con aprobar/rechazar/comentar y navegación entre pendientes.
- Exportación a Excel (matriz de estatus por alumno/documento) y a Word (seguimiento individual).
- Papelera con restauración y purga automática de documentos eliminados.

**Portal del estudiante** (rol por defecto)
- Alta y edición de perfil (datos personales y académicos).
- Subida de documentos según el modo de carga configurado por el admin, con vista previa de PDF/Word en el propio navegador.
- Notificaciones de estatus del perfil y de cada documento (campanita in-app + push del navegador), ver [Notificaciones push](#notificaciones-push).

## Stack tecnológico

- **Backend:** Laravel 12 (PHP 8.3)
- **Interactividad:** Livewire 3 + Flux UI
- **Frontend:** Tailwind CSS 4, compilado con Vite
- **Base de datos:** SQLite en desarrollo, PostgreSQL en producción
- **Autorización:** Spatie Laravel Permission (roles `admin` / estudiante)
- **Documentos:** phpoffice/phpword (generación .docx), barryvdh/laravel-dompdf, LibreOffice headless (conversión .docx → PDF para el visor), maatwebsite/excel (exportación)
- **Notificaciones:** canal `database` nativo de Laravel + `laravel-notification-channels/webpush` (Web Push API, VAPID)
- **Entorno local:** Docker

## Requisitos

- Docker
- (Alternativamente, sin Docker: PHP 8.3+, Composer, Node 20+, y las extensiones `pdo_sqlite`, `bcmath`, `openssl`, `gd`, `intl`, `zip`, `mbstring`)

## Instalación local

```bash
git clone <url-del-repo> servicio
cd servicio
cp .env.example .env

# Construir la imagen de desarrollo (PHP 8.3 + Node + LibreOffice)
docker build -f Dockerfile.dev -t servicio-dev .

# Levantar el contenedor de la app
docker run -d --name servicio-app -p 8000:8000 -v "$(pwd)":/app servicio-dev \
  sh -c "composer install && npm install && php artisan key:generate && \
         touch database/database.sqlite && php artisan migrate --seed && \
         php artisan serve --host=0.0.0.0 --port=8000"

# Vite en modo desarrollo (assets con hot reload)
docker run -d --name servicio-vite -p 5173:5173 -v "$(pwd)":/app servicio-dev \
  sh -c "npm run dev -- --host"
```

La app queda disponible en `http://localhost:8000`.

Para generar las llaves de notificaciones push (una sola vez, se guardan en `.env`):

```bash
docker exec servicio-app php artisan webpush:vapid
```

## Variables de entorno

Además de las estándar de Laravel (`APP_*`, `DB_*`, `SESSION_*`), este proyecto usa:

| Variable | Para qué |
|---|---|
| `VAPID_SUBJECT` | Contacto (`mailto:`) requerido por el estándar Web Push |
| `VAPID_PUBLIC_KEY` / `VAPID_PRIVATE_KEY` | Par de llaves para firmar las notificaciones push (generarlas con `php artisan webpush:vapid`, **nunca subirlas al repo**) |

## Scripts disponibles

```bash
composer dev        # server + queue listener + vite, todo junto (desarrollo)
composer test        # limpia config y corre el test suite
npm run dev           # solo Vite (hot reload)
npm run build          # build de producción de los assets
```

## Testing

```bash
docker exec servicio-app php artisan test
```

## Estructura del proyecto

```
app/
  Livewire/
    Admin/          # Catálogos (campus, carreras, semestres)
    Dashboard/       # Panel del admin: periodos, alumnos, documentos, revisión
    Students/        # Portal del estudiante: perfil, documentos, notificaciones
    Settings/        # Perfil de usuario, contraseña
  Models/            # Student, Document, File, Period, Campus, Career, Semester, User
  Notifications/      # Notificaciones al estudiante (perfil/documentos)
  Services/          # Generación de Word, conversión Docx→PDF, purga de papelera
resources/
  css/               # Tailwind + estilos propios (sidebar.css)
  js/                # app.js: visor de PDF/Word, notificaciones push
  views/livewire/     # Vistas Blade de cada componente Livewire
public/sw.js          # Service worker de notificaciones push
```

## Notificaciones push

El estudiante recibe una notificación (campanita dentro del sitio + notificación nativa del navegador) cuando:

- Su perfil es aprobado o rechazado.
- Un documento suyo es aprobado o rechazado.
- Se agrega un comentario a uno de sus documentos.

Se implementó sin dependencia de un worker de colas (el proyecto no corre uno): el envío es síncrono en la misma petición del admin, e instantáneo en el caso normal porque el canal Web Push no hace ninguna llamada de red si el estudiante no tiene una suscripción activa. Cuando sí la tiene, el propio paquete atrapa cualquier fallo de envío (incluida una suscripción vencida, que se borra sola) sin afectar la acción del admin.

La campanita no usa `wire:poll`: se refresca al navegar y, si la pestaña está abierta, al vuelo cuando llega un push (el service worker le avisa a la página por `postMessage`).

## Despliegue

Configurado para [Render](https://render.com) vía `render.yaml` (build, migraciones y variables de entorno, incluyendo `VAPID_PUBLIC_KEY`/`VAPID_PRIVATE_KEY` como secretos que se capturan desde el dashboard de Render, nunca desde el repo).
