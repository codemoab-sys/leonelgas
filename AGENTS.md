# Leonel Gas - AGENTS.md

## Project structure

- `./` — landing page (static HTML, Bootstrap 5 CDN)
- `./sistema/` — PHP MVC app (clientes, ubicaciones, ventas)

## Sistema — PHP MVC (no framework)

- **Router**: `sistema/index.php` — front controller, action via `?action=Controller.method`
- **Controllers**: `sistema/controllers/` — `{Name}Controller.php`, class `{Name}Controller`
- **Models**: `sistema/models/` — plain PHP, raw PDO queries
- **Views**: `sistema/views/{controller_name}/` — PHP + HTML partials
- **Config**: `sistema/config/database.php` (singleton PDO), `sistema/config/helpers.php` (`baseUrl()`, `assetUrl()`, `jsonResponse()`)
- **Assets**: `sistema/assets/js/app.js` (jQuery + vanilla), `sistema/assets/css/styles.css`
- **Public actions** (no auth): `auth.login`, `auth.entrar`
- **Login hardcoded**: usuario=`prueba`, password=`prueba123`
- **DB tables** in `miubicaciones` database, all prefixed `ubi_`: `ubi_cliente`, `ubi_cliente_ubicaciones`, `ubi_venta`
- **DB creds** via `sistema/.env` (gitignored), falls back to `root:''@127.0.0.1`

## Deployment quirks (subdirectory `/sistema/`)

- All URLs (PHP redirects + JS) must include `/index.php` explicitly, e.g. `baseUrl() . '/index.php?action=...'`, never `baseUrl() . '/?action=...'` (server doesn't serve `index.php` as default in subdirectory)
- `sistema/index.htm` exists as meta-refresh fallback for `/sistema/` directory access
- `sistema/.htaccess` has `RewriteBase /sistema/` and rewrite rules for clean-ish URLs
- Uploaded photos stored in `sistema/uploads/fachadas/`

## Mobile-first features

- GPS capture: `navigator.geolocation.getCurrentPosition()` with `enableHighAccuracy: true`
- Camera: `<input type="file" accept="image/*" capture="environment">`
- Google Maps navigation: `https://www.google.com/maps/dir/{lat_current},{lng_current}/{lat_client},{lng_client}`
- WhatsApp: `https://wa.me/51{celular}?text=...`
- Theme toggle stored in `localStorage` key `theme`

## JS conventions

- `BASE_URL` = `baseUrl()` (defined in view template before `app.js` load)
- All AJAX calls use `BASE_URL + '/index.php?action=...'` (never `BASE_URL + '/?action=...'` — the `/sistema/` subdirectory doesn't serve `index.php` by default)
- Modal pattern: `#modalId.fadeIn(150)` / `cerrarModal('modalId')`

## Commands

- No build, no test, no lint — deploy via git push to shared hosting (cPanel Git)
- No npm dependencies
