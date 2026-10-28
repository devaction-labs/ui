---
kind: component
group: Base
components: [banner]
---
# Banner · `<x-ui::banner>`

A notice strip that applies to the whole page: news, instability, billing or a lost connection.

## Examples

### Variants

info for news, warning for instability, danger for things that block (role="alert") and offline for when the connection drops.

```blade
<div class="flex w-full flex-col gap-2">
    <x-ui::banner heading="New:" text="automatic review now runs on draft PRs." action="See changelog" href="#" />
    <x-ui::banner variant="warning" heading="GitHub API is unstable." text="Reviews may be delayed up to 10 minutes." action="GitHub status" href="#" />
    <x-ui::banner variant="danger" heading="Your plan expires in 3 days." text="After that, new PRs won’t be reviewed." action="Renew plan" href="#" />
    <x-ui::banner variant="offline" heading="No connection." text="Your changes are saved and will sync when you’re back." action="Try now" x-on:click="window.location.reload()" />
</div>
```

### Dismissible

dismissible shows the × and dispatches banner-dismissed, so you can remember the person has seen it.

```blade
<x-ui::banner heading="Dark theme is here." dismissible x-on:banner-dismissed="console.log('seen')" class="w-full">
    Switch it in the profile menu or let it follow the system.
</x-ui::banner>
```

## API

wire: and x-on: attributes go to the action button when there is no href; the others go to the strip.

### `x-ui::banner`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | string | `info` | info · warning · danger · offline. |
| `heading · text` | string | `null` | Bold title and text; the slot also becomes text. |
| `action · href` | string | `null` | Action on the right: a link with href, a button without. |
| `icon` | string | `per variant` | Replaces the icon. |
| `dismissible` | boolean | `false` | Close button; dispatches banner-dismissed. |

## Accessibility

- danger uses role="alert" (read immediately); the others use role="status".
- Color is never the only cue: there is always an icon and a title.
- The × has an accessible label ("Dismiss notice" in English, translated with the app locale).
