<?php

declare(strict_types=1);

namespace CastorTasks;

use Castor\Context;
use function Castor\run;

const DEV_COMPOSE = 'docker compose';
const PROD_COMPOSE = 'docker compose -f compose.yaml -f compose.prod.yaml';
const PHP_SERVICE = 'php';

function ensure_data_dir(): void
{
    run('mkdir -p data && touch data/app');
}

function dev_compose(string $command): void
{
    run(DEV_COMPOSE . ' ' . $command);
}

/**
 * Like dev_compose(), but allocates a host TTY for the subprocess so nested
 * `docker compose exec -it …` gets a real pseudo-terminal (readline, tab completion).
 */
function dev_compose_interactive(string $command): void
{
    run(DEV_COMPOSE . ' ' . $command, (new Context())->withTty(true));
}

function prod_compose(string $command): void
{
    run(PROD_COMPOSE . ' ' . $command);
}

function dev_php_exec(string $command): void
{
    dev_compose(sprintf('exec -u $(id -u):$(id -g) %s %s', PHP_SERVICE, $command));
}

/**
 * @param array<string, mixed> $input
 */
function mate_tool_exec(string $tool, array $input = []): void
{
    $root = \dirname(__DIR__);
    $json = $input === [] ? '{}' : json_encode($input, \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR);
    $cmd = sprintf(
        'cd %s && ./mate/mate-tool-call.sh %s %s',
        escapeshellarg($root),
        escapeshellarg($tool),
        escapeshellarg($json),
    );
    run($cmd);
}

function prod_php_exec(string $command): void
{
    prod_compose(sprintf('exec %s %s', PHP_SERVICE, $command));
}

/**
 * @param list<string> $allowed
 */
function mate_tools_validate_format(string $format, array $allowed): string
{
    if (!\in_array($format, $allowed, true)) {
        throw new \InvalidArgumentException('Invalid format; allowed: '.implode(', ', $allowed));
    }

    return $format;
}

function mate_tools_validate_tool_name(string $toolName): void
{
    if ('' === $toolName || !preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_.-]*$/', $toolName)) {
        throw new \InvalidArgumentException('Invalid tool name.');
    }
}

function stop_conflicting_dev_port_containers(): void
{
    run(<<<'BASH'
sh -lc 'set -eu;
project_name="${COMPOSE_PROJECT_NAME:-$(basename "$PWD")}";
for port in "${HTTP_PORT:-8080}" "${HTTPS_PORT:-8443}" "${MAILER_SMTP_PORT:-1025}" "${MAILER_UI_PORT:-8025}"; do
  [ -n "$port" ] || continue;
  for container_id in $(docker ps --filter "publish=${port}" --format "{{.ID}}"); do
    owner="$(docker inspect --format "{{ index .Config.Labels \"com.docker.compose.project\" }}" "$container_id" 2>/dev/null || true)";
    if [ "$owner" != "$project_name" ]; then
      container_name="$(docker inspect --format "{{.Name}}" "$container_id" 2>/dev/null || true)";
      container_name="${container_name#/}";
      echo "Stopping container ${container_name:-$container_id} bound to host port ${port}";
      docker stop "$container_id" >/dev/null;
    fi;
  done;
done'
BASH);
}
