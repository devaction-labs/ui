---
id: T-006
status: DONE
progress: 2/2
updated: 2026-10-27
depends_on: T-005
---
# T-006 · Exemplos Form (22) + Navigation (7)

REQ-S4..S6. Mesmo formato/testes de T-005 (dataset pega os novos arquivos sozinho).

## Arquivos `skill/examples/components/`
autocomplete, choice, color-picker, combobox, copy-field, date-picker, date-range, dropzone, editor, filter-menu, input-group, money-input, number-input, otp-input, pillbox, segmented, select, signature-field, slider, switch, textarea, timer; breadcrumb, page-header, pagination, resizable, sidebar, stepper, steps.

## Notas
- `pagination`: construir `new LengthAwarePaginator([...], 120, 10, 3)` em `@php` (sem DB).
- Campos com `wire:model` ficam como atributo (render sem Livewire funciona — confirmado pelo padrão dos testes atuais que usam `Blade::render`).
- Remover `->todo()` de Form/Navigation no teste de pareamento.

## Done quando
- 29 arquivos; I2, D1–D3 e pareamento verdes p/ Form/Navigation.
