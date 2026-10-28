---
kind: template
group: Templates
components: [auth.heading, auth.layout, auth.login, auth.magic-link, auth.register, auth.reset-password, auth.social]
---
# Auth · `<x-ui::auth>`

Ready-made authentication screens: login, registration, magic link and password reset, in a centered or split layout. They work with POST and a CSRF token or with Livewire.

## Examples

### Login

Centered layout: a card with a 6 px border over the frame. The fields are named email, password and remember, so validation errors show up on them.

```blade
<x-ui::auth.layout brand="Acme CRM" href="#" :fullscreen="false" class="w-full rounded-[18px]">
    <x-ui::auth.login forgot-href="#" register-href="#" magic-href="#" google="#" github="#" />
    <x-slot:footer>By signing in, you agree to the terms of use.</x-slot:footer>
</x-ui::auth.layout>
```

### Registration in the split layout

variant="split" puts the form on the left and a testimonial panel on the right (desktop only). The aside slot replaces the panel content.

```blade
<x-ui::auth.layout variant="split" brand="Acme CRM" href="#" :fullscreen="false" class="w-full overflow-hidden rounded-[18px] border border-line">
    <x-ui::auth.register login-href="#" terms-href="#" google="#" />
</x-ui::auth.layout>
```

### Magic link and password reset

Each flow has the form and the confirmation (sent). The reset has the request and reset steps.

```blade
<div class="grid w-full gap-4 lg:grid-cols-2">
    <x-ui::auth.layout brand="Acme CRM" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.magic-link sent email="ana@acme.com" resend-action="#" login-href="#" />
    </x-ui::auth.layout>
    <x-ui::auth.layout brand="Acme CRM" href="#" :fullscreen="false" class="rounded-[18px]">
        <x-ui::auth.reset-password mode="reset" token="abc123" email="ana@acme.com" />
    </x-ui::auth.layout>
</div>
```

## API

With action, the form submits by POST with a CSRF token (Fortify or Breeze routes work as is). With Livewire, pass wire:submit="login" and model="form": the fields get wire:model="form.email" and so on. Screen texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::auth.layout`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | centered \| split | `centered` | Card in the middle of the frame, or form with a side panel. |
| `brand · href` | string | `DevAction · /` | Brand name and link. |
| `fullscreen` | boolean | `true` | Takes the full screen height. |
| `slots` | aside · footer | `—` | Split panel and small links. |

### `x-ui::auth.login`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `action · model` | string | `null` | POST with CSRF, or the wire:model prefix. |
| `heading · description` | string | `Sign in · …` | Top texts. |
| `forgot-href · register-href · magic-href` | string | `null` | Optional links. |
| `google · github` | string | `null` | Social login URLs. |

### `x-ui::auth.register`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `action · model · login-href · terms-href · google · github` | string | `null` | Like login; terms-href shows the terms checkbox. |

### `x-ui::auth.magic-link`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `action · model · login-href` | string | `null` | Email form. |
| `sent · email · resend-action` | boolean · string · string | `false · null · null` | Sent confirmation with resend. |

### `x-ui::auth.reset-password`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `mode` | request \| reset | `request` | Request the link or create the new password. |
| `token · email` | string | `null` | Data from the received link (reset). |
| `sent · action · model · login-href` | — | `—` | Confirmation and submission. |

### `others`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `auth.heading` | heading · description | `—` | h1 title and supporting text. |
| `auth.social` | google · github · divider | `—` | Other-account buttons and the "or" divider. |

## Accessibility

- Fields with visible labels, the right autocomplete (username, current-password, new-password) and autofocus on the first.
- The password has a show/hide button.
- Each screen’s title is the h1; confirmations say what happened and the next step.
- The reset confirmation does not reveal whether the email has an account.
