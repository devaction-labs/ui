{{-- Resizable: two panels with a draggable handle, a diff beside an AI summary, and a vertical split — see references/resizable.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::resizable default="58" min="30" max="75" persist="docs-pr-review" class="h-[300px] w-full">
        <x-ui::resizable.panel>
            <div class="font-mono text-[11px] text-muted">DIFF</div>
            <pre class="mt-2.5 font-mono text-xs leading-[1.9] text-ink-2"><span class="block bg-risk-bg text-risk-fg">-  'due_on' => ['nullable', 'date'],</span><span class="block bg-ok-bg text-ok-fg">+  'due_on' => ['required', 'date'],</span>   'amount' => ['required', 'integer'],</pre>
        </x-ui::resizable.panel>
        <x-ui::resizable.handle label="Diff size" />
        <x-ui::resizable.panel class="bg-ai-soft">
            <div class="font-mono text-[11px] text-accent">AI SUMMARY</div>
            <p class="mt-2.5 text-sm leading-[1.6] text-ink-2">The PR makes due_on required. Two blocking findings: a migration for old milestones is missing and the test does not cover a milestone without a date.</p>
        </x-ui::resizable.panel>
    </x-ui::resizable>

    {{-- Variation: vertical split, editor above preview --}}
    <x-ui::resizable vertical default="40" class="h-[260px] w-full max-w-md">
        <x-ui::resizable.panel class="text-sm">Editor</x-ui::resizable.panel>
        <x-ui::resizable.handle label="Editor height" />
        <x-ui::resizable.panel class="text-sm text-ink-3">Preview</x-ui::resizable.panel>
    </x-ui::resizable>
</div>
