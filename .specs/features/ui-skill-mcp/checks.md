---
status: DONE
progress: 23/23
updated: 2026-10-27
---
# Checks · ui-skill-mcp

Fonte: `validation.md` (verificador independente, 2026-10-27), `tests.md`, `tasks/README.md`.
Round 2 re-executado: pest 997 verde, phpstan/pint/ui:skill --check/diff/check_bundle OK. PASS = prova em `validation.md`.
Arquivos de teste citados sem diretório quando o nome é único em `tests/`.

## Checks por REQ

Cada linha: claim (uma frase observável) | prova (arquivo::teste ou comando) | status.

### Parte 1 · Skill (REQ-S1..S7)

| REQ | Claim | Prova | Status |
|---|---|---|---|
| S1 | A skill em `skill/` tem o mesmo conteúdo de `main` | I1 (`git log --follow`); R1 (diff vs main só índice) | PASS (conteúdo). Histórico `--follow` N/P até commit |
| S2 | `skill/SKILL.md` existe com front matter válido | `SkillCoverageTest.php:82` (V1) | PASS |
| S3 | Cada reference tem front matter e seção de índice | `SkillCoverageTest.php:115` (V2) | PASS |
| S4 | Cada reference aponta para um exemplo renderizável | `SkillExamplesLintTest.php:125,144`; `SkillTemplateExamplesTest.php:75,92` | PASS |
| S5 | Os 96 exemplos renderizam sem exceção em `en` | `SkillExamplesTest.php:34` (D1, I2) | PASS |
| S6 | Os exemplos usam só tokens e componentes do kit | `SkillExamplesLintTest.php:87,95,101` (D2–D3) | PASS |
| S7 | `SKILL.md` tem um link por reference, sem link duplicado | `SkillCoverageTest.php:138` (V3) | PASS |

### Parte 2 · MCP (REQ-M1..M10)

| REQ | Claim | Prova | Status |
|---|---|---|---|
| M1 | O servidor `devaction-ui` registra as 6 tools com nome exato | `ServerRegistrationTest.php:9,19` (I3, I4); E1 `StdioE2ETest.php:101` `toBe('DevAction UI')` | PASS |
| M2 | `list-components` lista componentes a partir do manifesto | `ReadToolsTest.php:10`; `ComponentManifestTest.php:53` (I5) | PASS |
| M3 | `get-component` devolve detalhe e, para nome inválido, sugere o mais próximo | `ReadToolsTest.php:33,42,48` (I6, I7) | PASS |
| M4 | `generate-component` monta markup válido a partir de props e slot | `MarkupBuilderTest.php:7-37,54,62,71` (B14, B15, S10, S11); `GenerateToolsTest.php:10` | PASS |
| M5 | Prop ou atributo fora do contrato é rejeitado antes de gerar markup | `MarkupBuilderTest.php:43` (B9), `:62` (chave inválida); `GenerateToolsTest.php:16,34` (S12) | PASS |
| M6 | Template que não compila devolve erro e não gera arquivo | `GenerateToolsTest.php:25` (`@if` sem `@endif`) | PASS |
| M7 | `generate-template` troca o placeholder de marca pelos valores passados | `GenerateToolsTest.php:34,40,45,57` (B11–B13) | PASS |
| M8 | `scaffold-component` sem `dry_run` não escreve nada e lista os arquivos planejados | `ScaffoldToolsTest.php:130,141,148` (S6, S7, S5); E4 `StdioE2ETest.php:116` snapshot de mtime antes==depois | PASS |
| M9 | Scaffold recusa path traversal, sobrescrita e escrita em `vendor/`, e exige campos obrigatórios | `ScaffoldToolsTest.php:64,74,84,95,108,219` (S1–S4); `ScaffolderEdgeCasesTest.php:133` (obrigatórios) | PASS |
| M10 | Nenhuma tool abre conexão de rede | `Http::preventStrayRequests()` nos 4 arquivos `tests/Feature/Mcp` (S8) + `NoNetworkTest.php:3` (S9) | PASS |

### Parte 3 · Manifesto e comando (REQ-F1..F6)

| REQ | Claim | Prova | Status |
|---|---|---|---|
| F1 | O parser lê props e slots dos `.blade` | `BladeComponentParserTest.php:8,30,37,43` (U1, U3–U6); `ComponentManifestTest.php:45` | PASS |
| F2 | O parser lê o cabeçalho `{{-- --}}` dos exemplos | `BladeComponentParserTest.php:18` (U2) | PASS |
| F3 | O manifesto contém todos os componentes gerados | `ComponentManifestTest.php:5` (U7) | PASS |
| F4 | O manifesto reflete o front matter das references | `ComponentManifestTest.php:24,30` (U8, U9) | PASS |
| F5 | `ui:skill` regenera o espelho e `--check` acusa drift com exit 1 | `SkillCommandTest.php:43,55,69,82,95` (V4–V6) | PASS |
| F6 | `composer test` roda `ui:skill --check` | `SkillCommandTest.php:43` (V4); `composer.json`; `ui:skill --check` exit 0 | PASS |

## Landing (arquivos por parte)

- **skill/**: `SKILL.md`, `references/*.md` (incluindo `field.md`, `icon.md`, `typography.md`), `examples/**/*.blade.php` (96 arquivos: 81 componentes, 9 templates, 6 layouts).
- **src/Support/**: `BladeComponentParser.php`, `ComponentManifest.php`, `SkillMirror.php` (novos).
- **src/Console/**: `SkillCommand.php` (novo, `ui:skill`); `IconCommand.php` (modificado: `is_array` redundante removido).
- **src/Mcp/**: servidor `UiServer`, tools de leitura, geração e scaffold, `MarkupBuilder`, `Scaffolder`.
- **src/UiServiceProvider.php**: registro opcional do servidor MCP.
- **resources/ui-manifest.json**: manifesto gerado (F3, F4).
- **resources/boost/skills/devaction-ui-development/**: espelho gerado de `skill/` (Boost). Só modificado, sem edição manual.
- **tests/**: `Feature/Mcp/` (ServerRegistration, ReadTools, GenerateTools, ScaffoldTools, StdioE2E), `Feature/SkillCommandTest.php`, `Feature/SkillExamplesTest.php`, `Feature/SkillExamplesLintTest.php`, `Feature/SkillTemplateExamplesTest.php`, `Feature/SkillCoverageTest.php`, `Unit/BladeComponentParserTest.php`, `Unit/ComponentManifestTest.php`, `Unit/MarkupBuilderTest.php`, `Pest.php`, `TestCase.php`; `testbench.yaml` na raiz.
- **Fora da DAG** (ajustes aprovados): `resources/views/components/pagination.blade.php`, `lang/pt_BR.json`, `tests/Feature/PaginationComponentTest.php`, `AGENTS.md`, `docs/`, `composer.json` (laravel/mcp em require-dev).

## Coverage join (REQ → TASK → TEST, 23/23)

| TASK | REQs | Testes |
|---|---|---|
| T-002 | F1, F2 | unit U1–U6 |
| T-003 | S1, S2, S3, S7 | integration I1; validation V1–V3 |
| T-004 | F3, F4, F5, F6 | unit U7–U9; validation V4–V6 |
| T-005..T-008 | S4, S5, S6 | integration I2; ui-design D1–D3 |
| T-009 | M1, M2, M3, M10 | integration I3–I7; security S8–S9 (S9 pendente) |
| T-010 | M4, M5, M6, M7 | business-rules B1–B13 |
| T-011 | M8, M9 | security S1–S7 |
| T-012 | F6, todos | e2e-regression R1; e2e-flow E1–E4 |

Cobertura: 23/23 REQs com task e teste. Dos 23, 23 PASS.

## Resultado da validação (validation.md)

- Pest: 997 passed (2644 assertions), 0 fail.
- PHPStan: `[OK] No errors` .
- Pint: passed.
- `ui:skill --check`: exit 0.
- `diff -r skill resources/boost/skills/devaction-ui-development`: vazio.
- Itens: PASS 87 · FAIL 0 · MISSING 0 · N/P 2 (I1, S1 histórico pré-commit).
- Veredito do verificador: APPROVED (round 2); faltas do round 1 resolvidas.
