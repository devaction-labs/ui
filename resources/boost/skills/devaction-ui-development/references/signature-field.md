# Signature field · `<x-ui::signature-field>`

A signature drawn with a finger or mouse, or typed. It outputs SVG, light enough to store next to the document.

## Examples

### Sign an amendment

Draw inside the dashed area; Clear erases everything. note explains what is stored along with it.

```blade
<x-ui::signature-field
    label="Amendment signature"
    note="Along with the image we store date, time, IP and the PDF hash."
    class="w-full max-w-md"
/>
```

### Type the name

mode="type" starts in typing mode; signer fills in the name.

```blade
<x-ui::signature-field mode="type" signer="Ana Souza" class="w-full max-w-md" />
```

## API

wire:model receives the SVG ("<svg …>…</svg>") or "" when empty. Validate with required on the server and store the SVG as text. Built-in texts (Draw, Type, Clear) follow the app locale.

### `x-ui::signature-field`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Area label. |
| `mode` | draw \| type | `draw` | Initial mode. |
| `signer` | string | `''` | Name in typing mode. |
| `note` | string | `null` | Small text below. |
| `name` | string | `null` | Hidden input with the SVG. |
| `error` | string | `null` | Error; with wire:model it comes from validation. |

## Accessibility

- People who cannot draw can type their name: both modes produce a valid signature.
- The modes are a radiogroup; the status ("Empty signature", "Ready to sign") is announced.
- The drawing area accepts mouse, pen and touch (pointer events, without scrolling the page).
