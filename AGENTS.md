# symfony-web-template

This repository is a reusable Symfony UX template.

## Which tool to use

- **Pure JS behavior, no server round-trip** -- use the `stimulus` skill
- **Navigation, partial page updates** -- use the `turbo` skill
- **Reusable static UI component** -- use the `twig-component` skill
- **Reactive component that re-renders on user input** -- use the `live-component` skill
- **Not sure which one fits** -- use the `symfony-ux` skill
- **Browser automation / UI testing** -- use Task tool with `subagent_type: "playwright-cli"`

## Key rules

- Always render `{{ attributes }}` on LiveComponent root elements.
- Prefer HTML Twig component syntax (`<twig:Alert />`).
- Use `data-model="debounce(300)|field"` for text fields in LiveComponents.
- Stimulus controllers must clean up listeners/observers in `disconnect()`.
- Turbo Frame IDs must match between page and response.
- Use Turbo Streams for multi-region updates; Frames for single-region updates.
- Prefer injecting `ClockInterface` for time-sensitive logic instead of calling system time directly.
- For infrastructure operations, use `make` targets.
- Never run Composer or PHP on the host for project operations.
- For browser verification, always use `playwright-cli` subagent.

## Docker setup

- Runtime stack: FrankenPHP (PHP 8.5), Symfony worker mode, Mercure, SQLite.
- SQLite file path: `data/app` (`DATABASE_URL=sqlite:///%kernel.project_dir%/data/app`).
- Keep `data/.gitignore` as `*` and `!.gitignore`.
- Dev compose: `compose.yaml` + `compose.override.yaml`.
- Prod-like compose: `compose.yaml` + `compose.prod.yaml`.

## Make flow

- First-time setup: `make setup`, then `make dev-bootstrap` (and `make doctrine-migrate` if Doctrine is used).
- Background workers: `make messenger-consume`.
- Local lifecycle: `make up`, `make down`, `make restart`, `make ps`.
- Prod-like lifecycle: `make up-prod`, `make down-prod`, `make restart-prod`, `make ps-prod`.
