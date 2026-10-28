---
id: T-002
status: DONE
progress: 5/5
updated: 2026-10-27
depends_on: T-001
---
# T-002 · `src/Support/BladeComponentParser.php`

REQ-F1, REQ-F2. Spec: [spec/03](../spec/03-manifest.md).

## Contrato
`parse(string $path, string $componentsRoot): array{name, tag, file, doc, props: list<array{name, attribute, default}>, attributes: list<string>, slots: list<string>, children: list<string>}`

## Passos (TDD)
1. RED: `tests/Unit/BladeComponentParserTest.php` com fixtures em `tests/Fixtures/components/` (`plain.blade.php` sem `@props`; `props.blade.php` com defaults `null,false,'base',[],__('Sign in')`; `group/index.blade.php` + `group/item.blade.php`). Pest: `pest()->extend(TestCase::class)->in('Feature', 'Unit')` em `tests/Pest.php`.
2. `name`: path relativo sem `.blade.php`, `/`→`.`, sufixo `.index` removido (`table/index` → `table`, `combobox/option/index` → `combobox.option`).
3. `doc`: conteúdo do primeiro `{{-- … --}}` do arquivo, trim por linha (indentação comum removida).
4. `props`: localizar `@props(` e balancear parênteses; `token_get_all('<?php '.$array.';')` → pares chave ⇒ default; literal (`null`,`true`,`false`, número, string) vira valor PHP; resto vira string crua normalizada. `attribute` = `Str::kebab($name)`.
5. `attributes`: regex `\$attributes->(?:get|has)\('([^']+)'` (dedupe, ordem). `slots`: `<x-slot:([a-z-]+)` no `doc` ∪ `isset\(\$([a-zA-Z]+)\)` ∪ `\$([a-zA-Z]+)->isNotEmpty\(\)` menos props, `slot`, `attributes`. `children`: tags dos irmãos na mesma pasta quando o arquivo é `index`.
6. GREEN + snapshot real: `button` → props inclui `{name: variant, attribute: variant, default: outline}`, `iconTrailing → icon-trailing`, attributes ⊇ `icon:trailing, tooltip:position, tooltip:kbd`.

## Done quando
- Testes U1–U6 verdes; PHPStan nível 7 sem erro no arquivo; Pint ok.
- Nenhuma execução de código do `.blade` (sem `eval`, sem `include`).
