# Symfony Web Template

Reusable Symfony 8 template with Docker (FrankenPHP), Make targets, Cursor/OpenCode setup, and production-friendly defaults.

## Included in this template

- Docker runtime: FrankenPHP + Mercure + SQLite-ready setup.
- Compose files: `compose.yaml`, `compose.override.yaml`, `compose.prod.yaml`.
- Developer workflow: `Makefile`, VS Code wrappers, PHPStan/CS Fixer defaults.
- Agent setup: `.cursor/skills`, `.opencode/skills`, `.opencode/agents`, `AGENTS.md`.
- Environment templates: `.env`, `.env.dev`, `.env.test`, `.env.prod`, `.env.prod.local.dist`.
- Generic docs: local setup, Mercure notes, and deployment guide.

## Quick start

```bash
make setup
make dev-bootstrap
```

`make dev-bootstrap` does not run database migrations.

If your project uses Doctrine migrations:

```bash
make doctrine-migrate
```

In another terminal:

```bash
make messenger-consume
```

Open `http://localhost:8080`.

## Daily run

```bash
make up
```

Useful lifecycle commands:

- `make down`
- `make restart`
- `make ps`
- `make logs`

## Placeholder replacement checklist

This template intentionally uses placeholders in some files.

- `{{PROJECT_PATH}}`: absolute local project path used by `.vscode/*.sh` wrappers.
- `{{APP_DOMAIN}}`: production domain example in `.env.prod.local.dist`.

Suggested replacement command:

```bash
rg -l '\{\{PROJECT_PATH\}\}|\{\{APP_DOMAIN\}\}' . | xargs sed -i "s#{{PROJECT_PATH}}#/absolute/path/to/your/project#g; s#{{APP_DOMAIN}}#app.example.com#g"
```

## Important files

- `AGENTS.md`
- `docs/setup.md`
- `docs/server-deployment.md`
- `docs/mercure.md`
