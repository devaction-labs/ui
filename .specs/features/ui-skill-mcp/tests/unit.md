---
status: DONE
progress: 9/9
updated: 2026-10-27
---
# Unit

| ID | Alvo | Given/When | Then |
|---|---|---|---|
| U1 | BladeComponentParser | fixture `plain.blade.php` sem `@props` | `props=[]`, `doc` = texto do comentário |
| U2 | BladeComponentParser | defaults `null,false,'base',[],__('Sign in')` | `null`, `false`, `"base"`, `"[]"`, `"__('Sign in')"` |
| U3 | BladeComponentParser | `group/index.blade.php` | `name=group`, `children=[group.item]` |
| U4 | BladeComponentParser | `combobox/option/index.blade.php` real | `name=combobox.option` |
| U5 | BladeComponentParser | `button.blade.php` real | props `variant` default `outline`; attribute `icon-trailing`; attributes ⊇ `icon:trailing, tooltip:position, tooltip:kbd` |
| U6 | BladeComponentParser | arquivo com `<x-slot:actions>` no doc e `isset($footer)` | `slots=[actions, footer]` |
| U7 | ComponentManifest | `find('table.row')` | group `Data`, reference `skill/references/data-table.md`, example de `data-table` |
| U8 | ComponentManifest | `build()` 2× | JSON byte a byte igual |
| U9 | ComponentManifest | `build()` | componentes ordenados por `name`; chaves ordenadas |
