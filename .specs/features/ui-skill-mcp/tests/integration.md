---
status: DONE
progress: 7/7
updated: 2026-10-27
---
# Integration

| ID | Cenário | Esperado |
|---|---|---|
| I1 | `git log --follow --oneline skill/references/button.md` (script de verificação manual, registrado na T-003) | ≥ 1 commit anterior a 2026-10-27 |
| I2 | dataset `skill/examples/**/*.blade.php`, locale `en`, `Blade::render()` | 96/96 sem exceção, saída não vazia |
| I3 | app Testbench com laravel/mcp | `Registrar::getLocalServer('devaction-ui')` callable |
| I4 | leitura estática de `src/UiServiceProvider.php` | sem `use Laravel\Mcp`; registro dentro de `class_exists('Laravel\Mcp\Facades\Mcp')` |
| I5 | `UiServer::tool(ListComponentsTool, ['group'=>'Form'])` | contém `combobox`; não contém `"modal"` |
| I6 | `UiServer::tool(GetComponentTool, ['name'=>'button'])` e `['name'=>'data-table']` | JSON com `variant`/`outline` + exemplo; segundo resolve reference |
| I7 | `GetComponentTool ['name'=>'buton']` | erro `Unknown component "buton". Did you mean: button` |

```gherkin
Scenario: Success — read tools answer from the manifest
  Given the manifest is built
  When I call "get-component" with name "button"
  Then I see the prop "variant" with default "outline" and the example markup
Scenario: Error — unknown component
  When I call "get-component" with name "buton"
  Then the response is an error suggesting "button"
Scenario: Deviation — app without laravel/mcp
  Given laravel/mcp is not installed
  When the app boots
  Then no exception is thrown and no MCP server is registered
```
