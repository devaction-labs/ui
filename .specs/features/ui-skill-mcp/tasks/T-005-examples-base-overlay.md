---
id: T-005
status: DONE
progress: 4/4
updated: 2026-10-27
depends_on: T-003
---
# T-005 · Exemplos Base (21) + Overlay (14) + testes de exemplo

REQ-S4, S5, S6. Formato: [spec/01 § Formato de um exemplo](../spec/01-skill-folder.md).

## Passos
1. RED: `tests/Feature/SkillExamplesTest.php` — dataset `glob(skill/examples/**/*.blade.php)`; cada um: `app()->setLocale('en')`, `Blade::render(file_get_contents($f))` sem exceção (I2/D1). `tests/Feature/SkillExamplesLintTest.php` — D2 sem `/\b(bg|text|border|ring|fill|stroke)-(red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose|slate|gray|zinc|neutral|stone)-\d{2,3}\b/` e sem `dark:`; D3 todo `<x-ui::button` sem slot com `icon=` tem `aria-label`. Teste de pareamento: toda reference `kind: component` do grupo coberto tem exemplo (S4).
2. Um arquivo por reference em `skill/examples/components/{ref}.blade.php` para: accordion, alert, avatar, badge, banner, button, callout, code-block, description-list, empty-state, kbd, panel, progress, reactions, separator, skeleton, spinner, stat, tabs, tag, timeline; command-palette, confirm-dialog, context-menu, cookie-consent, danger-dialog, drawer, dropdown, hover-card, modal, notification-center, popover, progress-toast, toast-group, tooltip.
3. Cada exemplo: 1ª linha comentário-título; mostra o uso principal + 1 variação relevante (ex.: modal com trigger + form curto). Fonte do conteúdo: blocos ```blade da reference (adaptar p/ autocontido).
4. GREEN: 35/35 renderizam; lint verde.

## Done quando
- 35 arquivos; testes de exemplo e lint verdes; pareamento verde para Base/Overlay (grupos restantes marcados `->todo()` até T-006..T-008).
