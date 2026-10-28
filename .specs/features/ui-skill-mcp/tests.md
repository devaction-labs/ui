---
status: DONE
progress: 9/9
updated: 2026-10-27
---
# Tests — ui-skill-mcp

Pest + Testbench (PHP 8.5). Ferramenta única de "suite verde": `composer test` (Pint → PHPStan → `ui:skill --check` → Pest). Não há HTTP/tela ⇒ sem Playwright/k6 (motivo em cada arquivo).

| Tipo | Arquivo | IDs | Qtde |
|---|---|---|---|
| unit | [tests/unit.md](tests/unit.md) | U1–U9 | 9 |
| integration | [tests/integration.md](tests/integration.md) | I1–I7 | 7 |
| e2e-flow | [tests/e2e-flow.md](tests/e2e-flow.md) | E1–E4 | 4 |
| validation | [tests/validation.md](tests/validation.md) | V1–V6 | 6 |
| business-rules | [tests/business-rules.md](tests/business-rules.md) | B1–B13 | 13 |
| ui-design | [tests/ui-design.md](tests/ui-design.md) | D1–D3 | 3 (D1 = 96 casos) |
| load-k6 | [tests/load-k6.md](tests/load-k6.md) | — | N/A |
| security | [tests/security.md](tests/security.md) | S1–S9 | 9 |
| e2e-regression | [tests/e2e-regression.md](tests/e2e-regression.md) | R1–R3 | 3 |

## Coverage plan (≥ 93%)
Alvo: linhas de `src/Mcp/**`, `src/Support/BladeComponentParser.php`, `ComponentManifest.php`, `SkillMirror.php`, `src/Console/SkillCommand.php`. Cada ramo de erro tem teste (tool inexistente, prop inválida, compile fail, modo livewire inválido, nome inválido, arquivo existente, fora do checkout, drift). Ramos não cobertos aceitos: nenhum. Medição: `pest --coverage --min=93` (driver pcov/xdebug — T-001 verifica).

## Baseline
Registrada em `baseline.txt` (T-001). Só falhas novas contam.

## Browser smoke
N/A — feature não cria rota HTTP nem tela. Exemplos são validados por render server-side (D1). Prova visual opcional: renderizar exemplos numa página do app de demonstração do monorepo (fora deste pacote) — fora de escopo.
