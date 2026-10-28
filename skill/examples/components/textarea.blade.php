{{-- Textarea: multi-line field with a counter, a footer with actions, and auto-height — see references/textarea.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::textarea
        label="Milestone scope"
        description="Describe what is included. The AI uses this text to know what is out of scope."
        maxlength="500"
        rows="5"
        class="w-full"
    >
        Tithe entries, cash reconciliation and the treasurer report.
        <x-slot:footer>
            <x-ui::button variant="ghost" size="sm" icon="attachment-01" tooltip="Attach file" />
            <x-ui::button variant="ghost" size="sm" icon="at" tooltip="Mention" />
            <x-ui::button variant="ai" size="sm" icon="sparkles">Improve with AI</x-ui::button>
        </x-slot:footer>
    </x-ui::textarea>

    {{-- Variation: auto-height without resize, and an error message --}}
    <div class="grid w-full max-w-xl gap-4">
        <x-ui::textarea rows="auto" placeholder="Write a note about the contact…" />
        <x-ui::textarea label="Note" error="Write at least 10 characters." resize="none" rows="3">Ok</x-ui::textarea>
    </div>
</div>
