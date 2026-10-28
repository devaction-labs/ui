---
id: T-011
status: DONE
progress: 5/5
updated: 2026-10-27
depends_on: T-010
---
# T-011 · `Scaffolder`, `scaffold-component`, `scaffold-template`

REQ-M8, M9. **Security-sensitive** (escreve arquivos). Casos: [tests/security.md](../tests/security.md).

## Passos (TDD)
1. RED `tests/Feature/Mcp/ScaffoldToolsTest.php` (S1–S7) usando raiz temporária (`storage_path('framework/testing/kit')` com cópia mínima: `composer.json`, `tests/TestCase.php`, `skill/SKILL.md`), limpa em `afterEach` como `IconCommandTest`.
2. `src/Mcp/Scaffolder.php`: `__construct(string $root)`; `isKitCheckout()` = `! str_contains(str_replace('\\','/',$root), '/vendor/') && is_file("$root/tests/TestCase.php")`; `plan(kind, input): list<array{path, contents}>` (5 alvos de spec/02); `write(plan)`: para cada alvo, `realpath(dirname)` deve começar com `realpath($root)` (anti-traversal) e arquivo **não** pode existir — checagem de todos **antes** de escrever qualquer um (tudo-ou-nada); inserir linha no índice do SKILL.md sob `### {group}` em ordem alfabética.
3. Conteúdo gerado: blade com cabeçalho (summary + `<x-ui::{name}>…` + props) e `@props`; teste Pest (estilo `SeparatorComponentTest`); reference com front matter; exemplo renderizável.
4. Tools: `shouldRegister(): bool` → `Scaffolder::isKitCheckout()`; `dry_run` default `true` → `Response::json(['planned' => …])`; `false` → escreve, chama `Artisan::call('ui:skill')`, `Response::json(['created' => …])`.
5. Registrar no `UiServer::$tools` (6).

## Done quando
- S1–S7 verdes; scaffold real numa raiz temporária gera componente cujo teste gerado passa (`Blade::render` contém `Hello`).
- Revisão de segurança: nenhum caminho vindo do input é usado sem passar pela regex + prefixo de raiz.
