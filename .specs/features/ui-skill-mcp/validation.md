---
status: DONE
progress: 87/89
updated: 2026-10-27
---
# Validation · ui-skill-mcp (verificador independente)

## Verdict
**APPROVED** (round 2, re-verify pós-fix). Julgado na árvore de trabalho, sem alterar código/testes.
Contagem (REQ 23 + testes 66 = 89 itens): PASS 87 · FAIL 0 · MISSING 0 · N/P 2 (I1 e REQ-S1: histórico `--follow` só após commit). Falta residual baixa: S12 (ver Faults).

## Proofs run (round 2)
| Comando | Resultado |
|---|---|
| `php85 vendor/bin/pest --parallel` | 997 passed (2644 assertions), 0 fail |
| `phpstan analyse --memory-limit=1G` | `[OK] No errors` |
| `pint --test` | `passed` |
| `testbench ui:skill --check` | exit 0 ("em sincronia") |
| `diff -r skill resources/boost/skills/devaction-ui-development` | vazio |
| `check_bundle.py .specs/features/ui-skill-mcp` | OK (35 files, 0 errors, 0 warnings) |
| `.github/workflows/tests.yml` | YAML válido; step `Skill` (`composer test:skill`) com `if: matrix.dependency-version == 'prefer-stable'` (linhas 44-46) |
| spot-check 5 PASS antigos (U7, I6, S3, V3, R3) | cobertos pela suíte 997 verde |

## REQ matrix
| REQ | Prova (arquivo:linha) | Status |
|---|---|---|
| S1 | histórico `--follow` não provável antes do commit (= I1); conteúdo idêntico a main (diff R1) | N/P (aceito) |
| S2 | SkillCoverageTest.php:82 (V1) | PASS |
| S3 | SkillCoverageTest.php:115 (V2) | PASS |
| S4 | SkillExamplesLintTest.php:125,144 (ref↔exemplo); SkillTemplateExamplesTest.php:75,92 | PASS |
| S5 | SkillExamplesTest.php:34 (96 casos, en) | PASS |
| S6 | SkillExamplesLintTest.php:87,95,101 | PASS |
| S7 | SkillCoverageTest.php:138 (V3) | PASS |
| M1 | ServerRegistrationTest.php:9 (registrar) + :19 (estático; sem laravel/mcp só estático — aceito) | PASS |
| M2 | ReadToolsTest.php:10,17,23; ComponentManifestTest.php:53 | PASS |
| M3 | ReadToolsTest.php:33,42,48 | PASS |
| M4 | MarkupBuilderTest.php:7-37,54,62-71; GenerateToolsTest.php:10 | PASS |
| M5 | MarkupBuilderTest.php:42 (B9); GenerateToolsTest.php:16 | PASS |
| M6 | GenerateToolsTest.php:25 (`@if` sem `@endif` → "does not compile") | PASS |
| M7 | GenerateToolsTest.php:34,40,45,57 (brand `="Acme"` conforme spec atualizada) | PASS |
| M8 | ScaffoldToolsTest.php:130,141,148 | PASS |
| M9 | ScaffoldToolsTest.php:64,74,84,95,108,219; ScaffolderEdgeCasesTest.php:133 (campos obrigatórios) | PASS |
| M10 | `Http::preventStrayRequests()` nos beforeEach Mcp + `NoNetworkTest.php:3` (S9) | PASS |
| F1 | BladeComponentParserTest.php:8,30,37,43; ComponentManifestTest.php:45 | PASS |
| F2 | BladeComponentParserTest.php:18 (U2) | PASS |
| F3 | ComponentManifestTest.php:5 (U7) | PASS |
| F4 | ComponentManifestTest.php:24,30 (U8,U9) | PASS |
| F5 | SkillCommandTest.php:43,55,69,82,95 | PASS |
| F6 | SkillCommandTest.php:43 (V4) + `ui:skill --check` exit 0 re-rodado; `composer.json` test inclui check | PASS |

## Test-ID matrix
| ID | Teste | Status |
|---|---|---|
| U1–U6 | BladeComponentParserTest.php:8,18,30,37,43,51 | PASS |
| U7–U9 | ComponentManifestTest.php:5,24,30 | PASS |
| I1 | git log --follow impossível pré-commit | N/P (aceito) |
| I2 | SkillExamplesTest.php:34 (96/96) | PASS |
| I3 | ServerRegistrationTest.php:9 | PASS |
| I4 | ServerRegistrationTest.php:19 | PASS |
| I5 | ReadToolsTest.php:10 | PASS |
| I6 | ReadToolsTest.php:33,42 | PASS |
| I7 | ReadToolsTest.php:48 (`Did you mean: button`) | PASS |
| E1 | StdioE2ETest.php:101 `toBe('DevAction UI')` literal | PASS |
| E2 | StdioE2ETest.php:98 (6 tools, ordem exata) | PASS |
| E3 | StdioE2ETest.php:107 (`toBe('<x-ui::button variant="primary">Save</x-ui::button>')`) | PASS |
| E4 | StdioE2ETest.php:116-140 (5 planned; snapshot mtime de components/skill/tests antes==depois) | PASS |
| V1 / V2 / V3 | SkillCoverageTest.php:82 / 115 / 138 | PASS |
| V4 / V5 / V6 | SkillCommandTest.php:43 / 69 / 82 | PASS |
| B1–B10 | MarkupBuilderTest.php:7-47 (B3/B4 em `modal` real, :17,:22) | PASS |
| B14 / B15 | MarkupBuilderTest.php:62 (3 chaves maliciosas, `Invalid attribute name`) / :71 (`Invalid slot name`) | PASS |
| B11 / B12 / B13 | GenerateToolsTest.php:34 / 40 / 45 | PASS |
| D1 | SkillExamplesTest.php:34 | PASS |
| D2 / D3 | SkillExamplesLintTest.php:87,95 / 101 | PASS |
| S1–S4 | ScaffoldToolsTest.php:64,74,84,95 | PASS |
| S5 | ScaffoldToolsTest.php:108 (árvore idêntica antes/depois) | PASS |
| S6 / S7 | ScaffoldToolsTest.php:130 / 141 | PASS |
| S8 | `Http::preventStrayRequests()` no beforeEach dos 4 arquivos Mcp | PASS |
| S9 | NoNetworkTest.php:3 (varre src/Mcp + 4 arquivos, `hits === []`) | PASS |
| S10 / S11 | MarkupBuilderTest.php:62 / :71 (asserta exceção e mensagem) | PASS |
| S12 | GenerateToolsTest.php:34 (prop >2000 e slot >20000 → `assertHasErrors`); limites >50 props / >20 slots sem teste | PASS (parcial) |
| F1 fix | SupportEdgeCasesTest.php:138,152,169,180 (symlink prune, fonte com link, RuntimeException, reload mtime) | PASS |
| F3 fix | ScaffolderEdgeCasesTest.php:133,147,154,165,183 (obrigatórios, regra no erro, markdown, isKitCheckout, sync sem rebind) | PASS |
| F4 | TemplateMapTest.php:15,23 | PASS |
| R1 | `ui:skill --check` exit 0 + `diff -r` vazio + diff vs main só índice | PASS |
| R2 | suíte inteira 916 verde; I4 | PASS |
| R3 | IconCommandTest.php verde (100 passed no lote); IconCommand `is_array` removido (aceito) | PASS |

## Observable bullets
96 exemplos OK · check exit 0 OK · diff vazio OK · tools/list 6 (E2) OK · generate-component exato (E3) OK · dry-run nada escrito (S5/E4) OK · sem rede (S8 + S9) OK. "4 tools no app consumidor": coberto por S6/S7 (esconde 2 de autoria) — sem teste stdio direto, aceito.

## Faults
| # | Local | Sev | Nota |
|---|---|---|---|
| 1 | tests/Feature/Mcp/GenerateToolsTest.php:34 | info | S12 só testa prop >2000 e slot >20000; limites de contagem (>50 props, >20 slots) e valor de slot nomeado >20000 sem asserção. Follow-up opcional. |
| 2 | tests/Feature/Mcp/StdioE2ETest.php:116 | info | E4 usa snapshot de mtime, não `git status --porcelain`; equivalente prático, aceito. |

Falhas round 1 (S9, E1, E4, B3/B4): todas resolvidas.

## Gate
Pest 997/997 · PHPStan 0 · Pint ok · `ui:skill --check` 0 · espelho idêntico · bundle OK · CI YAML ok · sem commit feito. Liberado.
