---
status: DONE
progress: 17/17
updated: 2026-10-27
---
# Business rules — geração de markup e templates

`MarkupBuilder::build()` (B1–B10, B14–B17) e `GenerateTemplateTool` (B11–B13). Saída **exata**. B3/B4 usam `modal` real (`modal/index.blade.php:28,31`).

| ID | name | props / slot | Saída esperada |
|---|---|---|---|
| B1 | button | `{"variant":"primary"}`, slot `Save` | `<x-ui::button variant="primary">Save</x-ui::button>` |
| B2 | button | `{"icon":"add-01","aria-label":"Add"}` sem slot | `<x-ui::button icon="add-01" aria-label="Add" />` |
| B3 | modal | `{"name":"edit","flyout":true}`, slot `Body` | `<x-ui::modal name="edit" flyout>Body</x-ui::modal>` (`flyout => false` em `modal/index.blade.php:28`) |
| B4 | modal | `{"dismissible":false}` | `:dismissible="false"` (`modal/index.blade.php:31`) |
| B5 | number-input | `{"min":0,"max":10}` | `:min="0" :max="10"` |
| B6 | table | `{"paginate":"$this->orders"}` | `:paginate="$this->orders"` |
| B7 | button | `{"iconTrailing":"arrow-right-01"}` | `icon-trailing="arrow-right-01"` |
| B8 | button | `{"wire:click":"save","class":"w-full"}` | passthrough mantido na ordem |
| B9 | button | `{"varient":"primary"}` | erro contendo `Unknown prop "varient"` e `variant` |
| B10 | panel | slots `{"actions":"<x-ui::button>Edit</x-ui::button>"}`, slot `Body` | `<x-slot:actions>` antes de `Body`, indentado 4 espaços; compila |
| B11 | `auth.login` mode `livewire` | — | contém `wire:submit="login"`, `model="form"` e classe com `email`, `password`, `remember` |
| B12 | `pricing` mode `livewire` | — | erro `mode "livewire" is only available for auth.* templates` |
| B13 | `layout.classic` brand `Pedidos & Cia` | — | `name="Pedidos &amp; Cia"` no `sidebar.brand`; nenhum outro trecho alterado (diff = 1 linha) |
| B14 | button | `{"wire:x\"onmouseover=\"alert(1)":"a"}` | erro `Invalid attribute name "wire:x"onmouseover="alert(1)"."` (`MarkupBuilder.php:45-46`); sem markup |
| B15 | button | slots `{"a><script>x</script><x-slot:b":"x"}` | erro `Invalid slot name "a><script>x</script><x-slot:b".` (`MarkupBuilder.php:71-72`); sem markup |
| B16 | button | `{"title":"Save & close"}`, `{"title":"ana@acme.com"}` | `title="Save &amp; close"`; `title="ana&#64;acme.com"` (renderiza igual no browser) |
| B17 | button | `{"@click":"go()"}`, `@keydown.enter`, `@php` | `x-on:click="go()"`, `x-on:keydown.enter`, `x-on:php` (chaves `@` normalizadas) |

Valor com `"` (ex. `title="a \"b\""`) → escapado por `e()` (coberto em B13 com `&`).

```gherkin
Scenario: Success — markup for a primary button
  When I generate "button" with props {"variant":"primary"} and slot "Save"
  Then the markup is '<x-ui::button variant="primary">Save</x-ui::button>'
Scenario: Error — typo in a prop
  When I generate "button" with props {"varient":"primary"}
  Then I get an error listing the valid prop "variant"
Scenario: Deviation — livewire mode on a non-auth template
  When I generate the template "pricing" in mode "livewire"
  Then I get an error and no markup
```
