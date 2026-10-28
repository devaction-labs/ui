---
status: DONE
progress: 15/15
updated: 2026-10-27
---
# Security

Tools de autoria escrevem arquivos — painel security-engineer (julgado inline). Raiz temporária em todos os testes.

| ID | Ataque / caso | Esperado |
|---|---|---|
| S1 | `scaffold-component name="../evil"` | erro de validação (regex); 0 arquivos |
| S2 | `name="a/b"`, `name="Foo"`, `name="a..b"`, `name=""` | erro de validação |
| S3 | `name="button"` (já existe) | erro citando `resources/views/components/button.blade.php`; 0 arquivos (tudo-ou-nada) |
| S4 | 1 de 5 alvos existe (ex.: só a reference) | erro; nenhum dos outros 4 criado |
| S5 | `dry_run` omitido | `planned`; `git status --porcelain` igual antes/depois |
| S6 | raiz em `.../vendor/devaction/ui` | `scaffold-*` ausentes em `tools/list` (`shouldRegister` falso) |
| S7 | raiz sem `tests/TestCase.php` | idem S6 |
| S8 | toda a suíte MCP com `Http::preventStrayRequests()` | nenhuma requisição |
| S9 | grep estático em `src/Mcp`, `src/Support/{BladeComponentParser,ComponentManifest,SkillMirror}.php` por `Http::\|curl_\|fsockopen\|stream_socket_client\|https?://` (exceto `$schema`) | 0 ocorrências |
| S10 | attribute-name injection em `generate-component`: `props={"wire:x\"onmouseover=\"alert(1)":"a"}`, `props={"@php(system('id'))":true}` | erro `Invalid attribute name "…"` (`MarkupBuilder.php:45-46`); sem markup |
| S11 | slot-name injection: `slots={"a><script>x</script><x-slot:b":"x"}` | erro `Invalid slot name "…"` (`MarkupBuilder.php:71-72`); sem markup |
| S12 | DoS: `props` com >50 entradas; prop string >2000 chars; `slot` >20000 chars; `slots` com >20 entradas ou valor >20000 | erro de validação (`GenerateComponentTool.php:41-45`); nada compilado. `$`-props e slots são PHP/Blade confiáveis por design (descrição da tool) |
| S13 | Blade em valor string: `title="a{{ phpinfo() }}b"`, `{!! x !!}`, `@php(…)`, `{{-- --}}` | `{`→`&#123;`, `@`→`&#64;` após `e()`; compilado sem `<?php` extra (`MarkupBuilder.php:136`) |
| S14 | `\n` final em nome de atributo/slot (`"wire:x\n"`) | erro `Invalid attribute name`/`Invalid slot name` (regex com `\z`) |
| S15 | expressão `$…` com `"`: `$a" onmouseover="alert(1)` | erro `Bound expression for "title" cannot contain double quotes.` |

```gherkin
Scenario: Success — dry run writes nothing
  When I call "scaffold-component" with name "probe" without dry_run
  Then the response lists 5 planned files and the working tree is unchanged
Scenario: Error — path traversal
  When I call "scaffold-component" with name "../evil"
  Then the response is a validation error and no file exists outside the package
Scenario: Deviation — installed in vendor
  Given the package root is inside "vendor/"
  When I list the tools
  Then no scaffold tool is listed
```
