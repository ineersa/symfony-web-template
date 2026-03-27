# Development setup

This template runs on FrankenPHP with Symfony worker mode, built-in Mercure, and SQLite.

## Stack overview

- PHP runtime: FrankenPHP (`dunglas/frankenphp`) with PHP 8.5
- App mode: Symfony worker mode via `docker/frankenphp/worker.Caddyfile`
- Realtime: Mercure hub built into FrankenPHP/Caddy
- Database: SQLite at `data/app`
- Compose files:
  - Base: `compose.yaml`
  - Dev overrides: `compose.override.yaml`
  - Production-like overrides: `compose.prod.yaml`

## First-time local setup

1. Build images, start containers, and install dependencies:

   ```bash
   make setup
   ```

2. Run frontend build bootstrap:

   ```bash
   make dev-bootstrap
   ```

3. If your project uses Doctrine migrations, run:

   ```bash
   make doctrine-migrate
   ```

4. Run Messenger in another terminal:

   ```bash
   make messenger-consume
   ```

5. Optional while editing CSS/templates:

   ```bash
   make tailwind-watch
   ```

6. Open the app:

   - HTTP: `http://localhost:8080`
   - HTTPS: `https://localhost:8443`
   - Mailpit UI: `http://localhost:8025`

## Common local workflow

- Start: `make up`
- Stop: `make down`
- Restart: `make restart`
- Status: `make ps`
- Logs: `make logs`

## Production-like local run

```bash
make up-prod
```

Useful commands:

- `make down-prod`
- `make restart-prod`
- `make ps-prod`
- `make logs-prod`
- `make doctrine-migrate-prod`
