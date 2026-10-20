# Copy field · `<x-ui::copy-field>`

A value ready to copy: API key, webhook secret, invite link. Secrets show masked.

## Examples

### Masked secret

masked keeps only the prefix and the last 4 characters; the eye shows the full value and Copy turns into "Copied" for 1.6 s.

```blade
<x-ui::copy-field
    label="GitHub webhook secret"
    value="whsec_9f3c1e7a2b8d4f60c5e1"
    masked
    description="Shown in full only once. After that, only the last 4."
    class="w-full max-w-md"
/>
```

### Link

Without masked, the full value shows and is cut with an ellipsis when it doesn’t fit.

```blade
<x-ui::copy-field label="Invite link" value="https://app.devaction.com.br/invite/8KQ2-7HXP" class="w-full max-w-md" />
```

## API

### `x-ui::copy-field`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description` | string | `null` | Label and hint. |
| `value` | string | `—` | The copied text. |
| `masked` | boolean | `false` | Hides the value, with a show button. |
| `visible` | int | `4` | Characters visible at the end when masked. |
| `prefix` | string | `up to the first "_"` | Start that is always visible (whsec_, sk_live_…). |

## Accessibility

- The value sits in an <output> linked to the label and hint.
- Show has aria-pressed; the copy is announced by an aria-live region.
- Masking is visual only: the value is in the page so it can be copied. Don’t use it for secrets this person isn’t allowed to see.
