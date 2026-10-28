{{-- One-time code input: segmented digits with resend cooldown, error and success states — see references/otp-input.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="flex flex-col items-center gap-1 text-center">
        <span class="flex size-11 items-center justify-center rounded-xl bg-wrap"><x-ui::icon name="mail-01" /></span>
        <span class="mt-2 text-lg font-semibold">Enter the code</span>
        <span class="text-sm text-ink-3">We sent 6 digits to a***@devaction.com.br</span>
        <x-ui::otp-input value="4829" resend="sendCode" cooldown="42" class="mt-4" />
    </div>

    {{-- Variation: error and success states, and an alphanumeric field with a label --}}
    <div class="flex flex-col gap-8">
        <x-ui::otp-input value="482913" error="Wrong code. 2 attempts left." />
        <x-ui::otp-input value="482913" success="Verified. Signing in…" />
        <x-ui::otp-input wire:model="code" submit="verify" resend="sendCode" length="4" label="Invite code" mode="alphanumeric" />
    </div>
</div>
