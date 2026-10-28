---
id: T-010
status: DONE
progress: 5/5
updated: 2026-10-27
depends_on: T-009, T-006, T-007, T-008
---
# T-010 · `MarkupBuilder`, `generate-component`, `generate-template`

REQ-M4..M7. Casos exatos: [tests/business-rules.md](../tests/business-rules.md).

## Passos (TDD)
1. RED `tests/Unit/MarkupBuilderTest.php` com B1–B10 (tabela de entradas → saída exata).
2. `src/Mcp/MarkupBuilder.php` (sem dependência de MCP): ordem dos atributos = ordem recebida; chave camelCase → kebab; regras de valor (REQ-M4); validação de chave (REQ-M5) contra `props[*].attribute` ∪ `props[*].name` ∪ `attributes` ∪ passthrough; `slot` → conteúdo; `slots` → `<x-slot:nome>…</x-slot:nome>` antes do slot padrão, indentação 4 espaços; sem slot nem slots → tag autofechada `<x-ui::x … />`. Erro = `InvalidArgumentException` com mensagem `Unknown prop "varient" for x-ui::button. Valid: variant, size, icon, …`.
3. `GenerateComponentTool`: `find()` → `MarkupBuilder` → `Blade::compileString()` (try/catch `Throwable` ⇒ `Response::error("Generated markup does not compile: …")`) → `Response::text()`.
4. RED/GREEN `GenerateTemplateTool` (B11–B13): mapa fixo template → arquivo (`auth.login` → `templates/auth-login[.livewire].blade.php`, `layout.rail` → `layouts/rail.blade.php`…); `mode=livewire` fora de `auth.*` ⇒ erro; `brand` substitui todo valor de atributo exatamente `="Acme"` (regex `/="Acme"/`, valor escapado com `e()`); nada mais muda. Resposta = conteúdo do exemplo sem a 1ª linha de comentário + (livewire) bloco `// app/Livewire/Auth/Login.php` extraído do comentário de topo.
5. Registrar ambas no `UiServer::$tools`.

## Done quando
- B1–B13 verdes; tools aparecem em `tools/list` (4 no total).
