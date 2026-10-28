{{-- Copy field: read-only value with a copy button, masked secret or plain link — see references/copy-field.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::copy-field
        label="GitHub webhook secret"
        value="whsec_9f3c1e7a2b8d4f60c5e1"
        masked
        description="Shown in full only once. After that, only the last 4."
        class="w-full max-w-md"
    />

    {{-- Variation: plain link that can be copied in one click --}}
    <x-ui::copy-field label="Invite link" value="https://app.devaction.com.br/invite/8KQ2-7HXP" class="w-full max-w-md" />
</div>
