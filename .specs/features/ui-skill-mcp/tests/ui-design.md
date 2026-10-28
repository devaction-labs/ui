---
status: DONE
progress: 3/3
updated: 2026-10-27
---
# UI design (lint dos exemplos)

| ID | Regra (golden rules da skill) | Teste |
|---|---|---|
| D1 | Todo exemplo renderiza (96) — mesmo dataset de I2 | `SkillExamplesTest` |
| D2 | Só tokens: nenhum `(bg\|text\|border\|ring\|fill\|stroke)-{paleta}-{nn}` e nenhum `dark:` | `SkillExamplesLintTest`, falha cita arquivo + classe |
| D3 | `<x-ui::button` só-ícone (com `icon=`, sem slot) tem `aria-label` ou `tooltip` | `SkillExamplesLintTest` |

frontend-architect OFF (sem tela nova) — estas 3 regras cobrem a consistência visual dos exemplos.
