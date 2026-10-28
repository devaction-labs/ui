---
id: T-012
status: DONE
progress: 6/6
updated: 2026-10-27
depends_on: T-011
---
# T-012 · Docs, ADR, CI, e2e stdio

REQ-F6 + fecho de todos.

## Passos
1. `composer.json` scripts: `"test:skill": "testbench ui:skill --check"`; incluir em `test` (após `test:types`).
2. `tests/Feature/Mcp/StdioE2ETest.php` (E1–E4): `proc_open([PHP_BINARY, 'vendor/bin/testbench', 'mcp:start', 'devaction-ui'])`, enviar JSON-RPC `initialize` → `notifications/initialized` → `tools/list` → `tools/call`; ler respostas linha a linha com timeout 10 s; fechar stdin; assert exit limpo.
3. README: seções **Skill** (o que é `skill/`, como copiar p/ `.claude/skills` ou usar via Boost) e **MCP server** (`composer require laravel/mcp`, config JSON de spec/02, lista das tools, nota "authoring tools only in the kit checkout"), em inglês.
4. `resources/boost/guidelines/core.blade.php`: 1 linha mencionando o MCP `devaction-ui` (+ `ui:skill` regenera o espelho — editar só `skill/`).
5. `docs/adr/001-skill-mcp.md` com o conteúdo do ADR-001 (spec/05). `AGENTS.md` (pedido do usuário 2026-10-27, criado fora da DAG): revisar que comandos/tools citados batem com o código final.
6. Suíte completa em background (`composer test`); comparar com `baseline.txt`; reportar só falhas novas. Cobertura `pest --coverage --min=93` em `src/Mcp`, `src/Support/{BladeComponentParser,ComponentManifest,SkillMirror}.php`, `src/Console/SkillCommand.php`.

## Done quando
- E1–E4, R1 verdes; `composer test` verde (exceto baseline); cobertura ≥ 93% nos arquivos novos; resumo + sugestão de commit entregues ao usuário (sem commitar).
