---
id: T-009
status: DONE
progress: 5/5
updated: 2026-10-27
depends_on: T-004
---
# T-009 · `UiServer`, registro opcional, tools de leitura

REQ-M1, M2, M3, M10. Spec: [spec/02](../spec/02-mcp-server.md), [spec/04](../spec/04-technical.md).

## Passos (TDD)
1. `composer require --dev laravel/mcp:^0.6` (php85); `suggest` → `"laravel/mcp": "Servidor MCP local devaction-ui (^0.6)"`. `testbench.yaml` com providers `DevAction\Ui\UiServiceProvider`, `Laravel\Mcp\Server\McpServiceProvider`; `.gitattributes` `testbench.yaml export-ignore`.
2. RED `tests/Feature/Mcp/ServerRegistrationTest.php`: (I3) com laravel/mcp → `app(Registrar::class)->getLocalServer('devaction-ui')` não nulo; (I4) provider não importa `Laravel\Mcp` (assert estático: `src/UiServiceProvider.php` não contém `use Laravel\Mcp`) e registro está atrás de `class_exists('Laravel\Mcp\Facades\Mcp')`.
3. `src/Mcp/UiServer.php` (`#[Name('DevAction UI')]`, `#[Version]` = versão do pacote, `#[Instructions]` curto: "Use list-components/get-component before writing x-ui markup…"), `$tools` inicialmente 2.
4. RED `tests/Feature/Mcp/ReadToolsTest.php` com `UiServer::tool(ListComponentsTool::class, [...])`: (I5) `group=Form` → contém `combobox`, não contém `modal`; `query=DATA` → contém `table`; `group=Xyz` → `assertHasErrors`; (I6) `get-component name=button` → `assertSee('"variant"')`, `assertSee('outline')`, exemplo presente; `name=data-table` resolve; (I7) `name=buton` → erro com `Did you mean` e `button`.
5. GREEN: tools finas — validam com `$request->validate()`, delegam a `ComponentManifest::load()`; `Response::json()` p/ dados, `Response::error()` p/ falha; `#[IsReadOnly]` `#[IsIdempotent]` (conferir assinatura em `vendor/laravel/mcp/src/Server/Tools/Annotations`). `beforeEach(fn () => Http::preventStrayRequests())` em `tests/Feature/Mcp/*` (S8).

## Done quando
- I3–I7, S8 verdes; PHPStan nível 7 em `src/Mcp`; Pint ok.
- `vendor/bin/testbench mcp:inspector devaction-ui` (manual) lista 2 tools.
