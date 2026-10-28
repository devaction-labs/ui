---
status: DONE
progress: 6/6
updated: 2026-10-27
---
# Validation

| ID | Cenário | Esperado |
|---|---|---|
| V1 | front matter das 86 references | `kind` ∈ {component, template, layout, guide}; `group` não vazio; `components` lista (pode ser `[]` só em guide) |
| V2 | 211 blades (exceto `icons/`) × `components:` | cada um reivindicado 1×; falha lista órfãos e duplicados por nome |
| V3 | `references/*.md` × links no SKILL.md | cada reference linkada exatamente 1× |
| V4 | `ui:skill --check` no repo sincronizado | exit 0, nenhuma escrita (mtime inalterado) |
| V5 | `ui:skill --check` com manifesto alterado (raiz temporária) | exit 1, saída contém `resources/ui-manifest.json` |
| V6 | `ui:skill --check` com arquivo extra no espelho Boost | exit 1, saída contém o caminho extra |
