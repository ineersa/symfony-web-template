---
name: vera-mcp
description: Guides agents to use Vera indexed-code tools for fast repository understanding and precise code retrieval with predictable shallow passes. Use when users ask where logic lives, request architecture overviews, need cross-file implementation discovery, or need exact identifier/regex lookup (keywords: vera, search code, find usage, where is, architecture, overview, regex).
license: MIT
metadata:
  author: OpenCode
  version: "1.0"
---

# Vera MCP

Use Vera for read-only repository discovery before manual file digging.

## Quick start

```bash
vera_get_stats(path=/repo)
vera_get_overview(path=/repo)
vera_search_code(query="...", queries=["...", "..."], intent="...", scope="source", path="**/*", limit=5)
vera_regex_search(pattern="...", scope="source", context=2, limit=20)
```

## When to use

- Conceptual questions: "where is auth handled?", "how does this flow work?", "what updates this page?"
- Cross-file discovery in medium/large repositories.
- Exact token checks (imports, class names, TODO markers, config keys).
- First-pass triage before opening files with `Read`.

## Workflow (No Deep Mode)

1. Check index health with `vera_get_stats`.
2. Build project map with `vera_get_overview`.
3. Run `vera_search_code` with 2-3 varied queries and a clear `intent`.
4. Narrow results with `scope`, `path`, `lang`, `symbol_type`, and `limit`.
5. Validate exact text with `vera_regex_search`.
6. Open matched files with `Read` for final confirmation.

Checklist:

- Use iterative, targeted searches.
- Prefer multiple focused queries over one broad query.
- Keep limits small, then widen only if needed.
- Do not use deep or exhaustive exploration modes.

## Tool descriptions

- `vera_get_stats`: reports index health, language mix, and chunk counts. Use first.
- `vera_get_overview`: returns architecture snapshot (directories, entry points, hotspots). Use for orientation.
- `vera_search_code`: semantic retrieval for behavior/concept questions. Use for "how/where" discovery.
- `vera_regex_search`: exact regex matching with context. Use for known identifiers/text.
- `Read`: inspect returned files to confirm behavior. Use after Vera results.

## Output expectations

- Return top matches with short rationale and file paths.
- State confidence when results are sparse/noisy.
- If needed, suggest one refined follow-up query or filter change.
