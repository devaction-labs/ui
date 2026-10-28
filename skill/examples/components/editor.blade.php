{{-- Editor: rich text field with a toolbar, a prefilled value, and mentions and commands — see references/editor.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::editor
        label="Delivery notes"
        description="Explain what changed in this milestone. It goes to the client together with the sign-off."
        value="<h2>What changed in M3</h2><ul><li>Tithes and offerings entry with an <strong>audit trail</strong></li><li>Cash reconciliation by period</li><li>Monthly treasurer report as PDF</li></ul><p>Left out: <code>refunds</code>, which go into Amendment 01.</p>"
        class="w-full max-w-2xl"
    >
        <x-slot:actions>
            <x-ui::button size="sm" variant="ai" icon="sparkles">AI</x-ui::button>
        </x-slot:actions>
    </x-ui::editor>

    {{-- Variation: comment box with @ mentions and / commands --}}
    <x-ui::editor
        toolbar="bold italic code | bullet | link"
        placeholder="Write… use @ to mention and / for commands"
        :mentions="[
            ['value' => 'alex', 'label' => 'Alex', 'description' => 'Dev · online now', 'color' => 'ink'],
            ['value' => 'carlos', 'label' => 'Carlos André', 'description' => 'Dev · SGE', 'color' => 'blue'],
            ['value' => 'reviewer', 'label' => 'DevAction Reviewer', 'description' => 'AI · replies on the PR', 'icon' => 'sparkles', 'color' => 'ai'],
        ]"
        :commands="[
            ['label' => '/task', 'description' => 'Create a task', 'icon' => 'task-01', 'name' => 'task'],
            ['label' => '/tag', 'description' => 'Label', 'icon' => 'flag-02', 'name' => 'tag'],
        ]"
        x-on:editor-command="console.log('command', $event.detail.name)"
        class="w-full max-w-2xl"
    />
</div>
