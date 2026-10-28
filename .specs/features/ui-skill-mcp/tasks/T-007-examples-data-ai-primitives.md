---
id: T-007
status: DONE
progress: 2/2
updated: 2026-10-27
depends_on: T-005
---
# T-007 · Exemplos Data (11) + AI (3) + Primitives (3)

REQ-S4..S6. Mesmo formato/testes de T-005.

## Arquivos `skill/examples/components/`
activity, calendar, chart, checklist, data-table, diff, file-tree, kanban, pdf-viewer, properties, sortable-list; ai-prompt, aura, chat-bubble; field, typography, icon.

## Notas
- `data-table`: linhas em array `@php`; mostrar colunas ordenáveis + vazio (`<x-slot:empty>`), sem `:paginate` com `$this`.
- `chart`: séries inline; `pdf-viewer`: usar URL relativa fictícia (`/docs/contract.pdf`) — nada é baixado no render.
- Remover `->todo()` de Data/AI/Primitives no pareamento.

## Done quando
- 17 arquivos; I2, D1–D3 e pareamento verdes.
