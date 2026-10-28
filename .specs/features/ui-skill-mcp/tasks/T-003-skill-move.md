---
id: T-003
status: DONE
progress: 6/6
updated: 2026-10-27
depends_on: T-001
---
# T-003 · Mover skill para `skill/` + front matter + 3 references novas

REQ-S1, S2, S3, S7. Spec: [spec/01](../spec/01-skill-folder.md).

## Passos
1. `git mv resources/boost/skills/devaction-ui-development skill` (histórico preservado). O espelho volta em T-004 via `ui:skill`.
2. Em cada uma das 83 references, prefixar front matter `kind`, `group`, `components: [...]` (lista inline). Grupo = heading do índice do SKILL.md; `layouts.md`→`kind: layout, group: Layouts`; `auth.md`,`pricing.md`→`kind: template, group: Templates`; `livewire.md`,`theming.md`→`kind: guide, group: Guides, components: []`.
3. Criar `field.md` (field, label, error, description), `typography.md` (heading, text, eyebrow), `icon.md` (icon) — `kind: component, group: Primitives`, conteúdo no formato das existentes (título, descrição, `## Examples`, `## API` com tabela de props tirada do cabeçalho `{{-- --}}` do blade, `## Accessibility`), em inglês.
4. Verificar uso de `main`, `spacer`, `logo` (`grep -rn 'x-ui::main\|x-ui::spacer\|x-ui::logo' resources/views`): hoje usados em sidebar/auth/layouts ⇒ reivindicar em `layouts.md` (ou `sidebar.md` p/ `spacer` se só usado lá). Decidir pelo uso real e anotar.
5. `SKILL.md`: adicionar `### Primitives` e `### Layouts` no índice (links p/ 3 novas + layouts.md), seção "Guides" com livewire/theming; manter texto atual.
6. RED→GREEN: `tests/Feature/SkillCoverageTest.php` — (V1) front matter válido nas 86; (V2) 211/211 blades (exceto `icons/`) reivindicados exatamente 1×, mensagem lista órfãos/duplicados; (V3) todo `references/*.md` linkado 1× no SKILL.md. Leitor de front matter: função própria (chaves planas + lista inline `[a, b]`), sem dependência nova.

## Done quando
- V1–V3 verdes; `git log --follow skill/references/button.md` mostra commits antigos.
- Nenhum `.blade.php` alterado.
