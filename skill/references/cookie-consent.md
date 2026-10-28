---
kind: component
group: Overlay
components: [cookie-consent]
---
# Cookie consent · `<x-ui::cookie-consent>`

Cookie notice for privacy laws (GDPR, LGPD): accept all, necessary only, or choose by category. The choice is remembered.

## Examples

### With preferences

Preferences opens the categories; Necessary is always on. On this page the notice uses :fixed="false" and force to show inside the preview.

```blade
<x-ui::cookie-consent policy="#" :fixed="false" force />
```

### In the layout

Put it once in the layout: it stays fixed at the bottom until the person chooses. To respect the choice on the server, read the ui_cookie_consent cookie before turning on analytics scripts.

```blade
<x-ui::cookie-consent policy="/privacy" :fixed="false" force x-on:cookie-consent-updated="console.log($event.detail)">
    We use analytics cookies to improve the CRM. You choose.
</x-ui::cookie-consent>
```

```php
// bootstrap/app.php: the cookie is created by the browser, without encryption
->withMiddleware(function (Middleware $middleware): void {
    $middleware->encryptCookies(except: ['ui_cookie_consent']);
})

// in any controller, middleware or view
$consent = json_decode(request()->cookie('ui_cookie_consent', '{}'), true);

if ($consent['analytics'] ?? false) {
    // load the analytics script
}
```

## API

The ui_cookie_consent cookie stores {"necessary":true,"analytics":false,…,"at":"…"} for 1 year. Since it is created in the browser, exclude it from cookie encryption (encryptCookies except) to read it on the server. Default texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::cookie-consent`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `categories` | array | `analytics · marketing` | Optional categories: [key => [title, description]]. |
| `policy` | string | `null` | Privacy policy link. |
| `heading` | string | `We use cookies.` | Start of the text, in bold; the slot replaces the rest. |
| `fixed` | boolean | `true` | Fixed at the bottom; false keeps it in the page flow. |
| `force` | boolean | `false` | Shows even after the choice (preferences page). |
| `cookie-consent-updated` | event | `—` | detail with the choice for each category. |

## Accessibility

- It is a region with an accessible label ("Cookie notice" in English); it doesn’t block the page.
- Each category is a labelled switch; Necessary shows as locked.
- Declining is as easy as accepting: "Necessary only" sits next to "Accept all".
