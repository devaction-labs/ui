---
id: T-001
status: DONE
progress: 4/4
updated: 2026-10-27
depends_on: —
---
# T-001 · Ambiente PHP 8.5 + baseline

**Por quê:** testes exigem PHP 8.4+ (README "Development"); local é 8.3; `vendor/` ausente.

## Passos
1. `"$HOME/Library/Application Support/Herd/bin/php85" $(which composer) install` na raiz do pacote (ou `herd isolate 8.5`).
2. `php85 vendor/bin/pest --parallel` → salvar resumo (passed/failed/skipped) em `.specs/features/ui-skill-mcp/baseline.txt`.
3. `php85 vendor/bin/phpstan analyse --memory-limit=1G` e `vendor/bin/pint --test` → anexar ao baseline.
4. Checar driver de coverage (`php85 -m | grep -iE 'pcov|xdebug'`); sem driver → registrar e usar `--coverage` só no CI ou instalar pcov.

## Done quando
- `baseline.txt` existe com contagem de falhas pré-existentes (só falhas **novas** contam depois).
- Nenhum arquivo do pacote alterado (`git status` só mostra `.specs/` e `vendor/`, este ignorado).
