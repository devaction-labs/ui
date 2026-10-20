# OTP input · `<x-ui::otp-input>`

Verification code by email or SMS: one box per digit, pasting fills them all and phones suggest the code.

## Examples

### Enter the code

Six boxes in two groups. Pasting the whole code fills all 6; Backspace goes back one box; ← → move.

```blade
<div class="flex flex-col items-center gap-1 text-center">
    <span class="flex size-11 items-center justify-center rounded-xl bg-wrap"><x-ui::icon name="mail-01" /></span>
    <span class="mt-2 text-lg font-semibold">Enter the code</span>
    <span class="text-sm text-ink-3">We sent 6 digits to a***@devaction.com.br</span>
    <x-ui::otp-input value="4829" resend="sendCode" cooldown="42" class="mt-4" />
</div>
```

### Error and success

The error comes from error or from wire:model validation; success paints the boxes green.

```blade
<div class="flex flex-col gap-8">
    <x-ui::otp-input value="482913" error="Wrong code. 2 attempts left." />
    <x-ui::otp-input value="482913" success="Verified. Signing in…" />
</div>
```

### With Livewire

submit calls the method when the last box is filled, with no submit button. resend calls the resend method after the cooldown.

```blade
<x-ui::otp-input wire:model="code" submit="verify" resend="sendCode" length="4" label="Invite code" mode="alphanumeric" />
```

```php
public string $code = '';

public function verify(string $code): void
{
    $this->validate(['code' => ['required', 'size:4']]);

    if (! hash_equals(session('invite_code'), $code)) {
        $this->addError('code', 'Wrong code.');

        return;
    }

    $this->redirectRoute('dashboard');
}

public function sendCode(): void
{
    // generate and send a new code
}
```

## API

The kit texts (Resend, countdown, digit labels) follow the app locale: English by default, Portuguese with pt_BR.

### `x-ui::otp-input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `length` | int | `6` | Number of boxes. |
| `mode` | string | `numeric` | numeric (numeric keyboard) · alphanumeric. |
| `separator` | boolean | `true with 6` | Splits into two groups. |
| `label` | string | `null` | Group label. |
| `error · success` | string | `null` | Error message (or from wire:model) and success message. |
| `submit` | string | `null` | Livewire method called with the full code; without it, fires otp-complete. |
| `resend · cooldown` | string · int | `null · 60` | Resend method and wait in seconds; fires otp-resend. |

## Accessibility

- Each box has aria-label "Digit N of 6" inside a role="group" with the label.
- The first box has autocomplete="one-time-code": iPhone and Android suggest the code from the SMS.
- inputmode="numeric" opens the numeric keyboard on phones.
- The error uses role="alert" and is linked to the boxes with aria-describedby.
