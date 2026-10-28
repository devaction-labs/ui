---
id: T-008
status: DONE
progress: 3/3
updated: 2026-10-27
depends_on: T-005
---
# T-008 · Exemplos de templates (9) e layouts (6)

REQ-S4..S6.

## Arquivos
- `skill/examples/templates/`: `auth-login`, `auth-register`, `auth-magic-link`, `auth-reset-password`, `pricing` (`.blade.php`, POST com `action="#"`), e `auth-login`, `auth-register`, `auth-magic-link`, `auth-reset-password` `.livewire.blade.php` (`wire:submit` + `model="form"`; topo com comentário contendo a classe Livewire: propriedades `email`, `password`, `remember`… conforme `references/auth.md` § API).
- `skill/examples/layouts/`: `classic`, `rail` (`classic collapsed`), `topbar`, `split`, `centered`, `docs` — página completa (sidebar/header/page-header/conteúdo) usando só componentes do kit; `brand="Acme"` onde houver marca (usado por `generate-template brand`).

## Passos
1. Pareamento: `auth.md`/`pricing.md` ⇒ 5 arquivos POST; `layouts.md` ⇒ 6 layouts (lista fixa no teste).
2. Render + lint (dataset de T-005 já cobre).
3. Livewire: render direto também deve passar (atributos `wire:*` são texto).

## Done quando
- 15 arquivos; I2, D1–D3 e pareamento verdes; `brand="Acme"` presente em todos os que têm marca (grep).
