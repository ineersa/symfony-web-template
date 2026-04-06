# Castor command guide

This project uses [Castor](https://castor.jolicode.com/) as the only task runner.

## Install

```bash
curl "https://castor.jolicode.com/install" | bash
castor --version
```

## Task files

- Entry point: `castor.php`
- Shared helpers: `.castor/helpers.php`
- Generated Mate MCP tasks: `.castor/mate.generated.php` (`mate:*`, regenerated from Mate tool schemas)
- Hand-written Mate CLI helpers: `.castor/mate.devtools.php` (`mate:tools:list`, `mate:tools:inspect`)
- Development tasks: `.castor/dev.php` (`dev:*`)
- Production-like tasks: `.castor/prod.php` (`prod:*`)

## Common commands

### Development

- `castor dev:setup`
- `castor dev:bootstrap`
- `castor dev:up`
- `castor dev:down`
- `castor dev:restart`
- `castor dev:ps`
- `castor dev:logs`
- `castor dev:sh` (interactive: use a real terminal. Castor runs Docker with a **host TTY** (`Context::withTty(true)`) plus `docker compose exec -it` so Bash gets a PTY and **readline / basic tab completion** work. Optional Debian `bash-completion` is not installed in the image by default—only minimal completion without it. After entrypoint changes, rebuild/restart the PHP service.)

### Symfony AI Mate (generated `mate:*`)

Each Mate MCP tool is exposed as a Castor command with options derived from its JSON schema (no hand-written JSON). Discover and inspect:

```bash
castor list mate
castor mate:phpstan-analyse --help
castor mate:composer-require symfony/console --constraint='^8.0' --mode=summary
```

**LLM-friendly discovery (full descriptions, do not use `--short`):** Castor’s Markdown list includes each command’s description, usage line, and every option with defaults. Omit `--short` so that detail is kept.

```bash
castor list mate --format=md
```

That output is **large** (every `mate:*` command fully expanded). Prefer a lighter index when you only need names:

```bash
castor list mate --format=md --short
castor list mate --short
```

For a **single** tool, `castor mate:<name> --help` is smaller and usually enough.

Regenerate after changing Mate extensions or Mate versions:

```bash
castor dev:mate-generate-castor
```

List and inspect Mate MCP tools inside the PHP container (same as `vendor/bin/mate mcp:tools:*`):

```bash
castor mate:tools:list --format=toon    # default; also: table, json
castor mate:tools:inspect phpstan-analyse --format=json
```

For arbitrary tool calls with JSON, use **`mate/mate-tool-call.sh`** (or a generated **`castor mate:<tool-name>`** task).

### App and tooling

- `castor dev:composer-install`
- `castor dev:composer "require vendor/package"`
- `castor dev:console "about"`
- `castor dev:messenger-consume`
- `castor dev:test`
- `castor dev:test-coverage`
- `castor dev:cs-fix`
- `castor dev:phpstan`
- `castor dev:quality`

Useful console examples:

- `castor dev:console "doctrine:migrations:migrate --no-interaction"`
- `castor dev:console "doctrine:migrations:status"`
- `castor dev:console "tailwind:build --watch"`
- `castor dev:console "asset-map:compile"`

### Production-like

- `castor prod:build`
- `castor prod:up`
- `castor prod:down`
- `castor prod:restart`
- `castor prod:ps`
- `castor prod:logs`
- `castor prod:console "about"`
- `castor prod:config`

Useful production console example:

- `castor prod:console "doctrine:migrations:migrate --no-interaction"`

## Discover tasks

Use the **`list`** command with a namespace. **`castor dev`** alone is not a shortcut: Castor looks for a command named `dev`, which does not exist, so you only see generic application help.

```bash
castor list
castor list dev
castor list prod
castor list mate
```

Other list styles (see `castor list --help`):

```bash
castor list dev --format=md      # Markdown-friendly (good for LLMs / docs)
castor list mate --format=json   # Machine-readable
castor list dev --short          # Names only, no argument lines
castor list --raw                # One command name per line
```

For **Mate** tasks with **full** descriptions and option metadata (LLM-oriented, large output), use `castor list mate --format=md` **without** `--short` (see **Symfony AI Mate** above).

## Notes

- Docker commands are executed through `docker compose`.
- Dev tasks use `compose.yaml` + `compose.override.yaml`.
- Prod-like tasks use `compose.yaml` + `compose.prod.yaml`.
- User-mapped container commands use `$(id -u):$(id -g)` to keep file ownership correct.
- `castor dev:up` and `castor dev:restart` stop conflicting running containers from other compose projects if they already bind the configured dev ports (`HTTP_PORT`, `HTTPS_PORT`, `MAILER_SMTP_PORT`, `MAILER_UI_PORT`).
- `castor dev:setup` and `castor dev:bootstrap` also stop conflicting running containers from other compose projects before doing their work.
- `castor dev:bootstrap` checks DNS resolution for `github.com` inside the PHP container before `tailwind:build` and fails fast with a Docker DNS hint when resolution is broken.
- `castor dev:bootstrap` removes invalid cached Tailwind binaries (for example zero-byte files after interrupted downloads) before triggering a fresh build.
