{{-- AI prompt: composer with a toolbar badge, submit event, and a busy state with an attachment — see references/ai-prompt.md --}}
<x-ui::ai-prompt max="4000" class="max-w-xl" x-on:ai-prompt-submit="$toast({ text: 'Sent: ' + $event.detail.text, variant: 'ai' })">
    <x-slot:toolbar>
        <x-ui::badge color="warn" icon="sparkles">Reviewer · strict</x-ui::badge>
    </x-slot:toolbar>
</x-ui::ai-prompt>

{{-- Variation: busy composer with an attached file --}}
<x-ui::ai-prompt busy class="mt-8 max-w-xl">
    <x-slot:attachments>
        <span class="flex h-[52px] items-center gap-2 rounded-xl border border-line bg-subtle p-[5px] pr-3 text-[13px]">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-risk-bg font-mono text-[10px] font-semibold text-risk-fg">PDF</span>
            <span class="flex flex-col"><span class="font-medium text-ink">Amendment 02.pdf</span><span class="text-[11px] text-muted">1.8 MB</span></span>
        </span>
    </x-slot:attachments>
</x-ui::ai-prompt>
