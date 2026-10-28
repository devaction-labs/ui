{{-- Steps: vertical checklist of setup progress with states, actions and an error step — see references/steps.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::steps class="w-full max-w-sm">
        <x-ui::steps.item state="done" label="Account created" description="just now" />
        <x-ui::steps.item state="done" label="GitHub connected" description="6 repositories" />
        <x-ui::steps.item state="current" label="First project" description="SGE · Campo Limpo">
            <x-ui::button size="sm" variant="primary">Create project</x-ui::button>
        </x-ui::steps.item>
        <x-ui::steps.item label="Invite the team" description="0 of 2" />
        <x-ui::steps.item label="Contract with milestones" description="for due date reminders" />
    </x-ui::steps>

    {{-- Variation: history of a process, with an error state --}}
    <x-ui::steps class="w-full max-w-sm">
        <x-ui::steps.item state="done" label="Proposal sent" description="Oct 12" />
        <x-ui::steps.item state="error" label="Down payment" description="card declined · Oct 14" />
        <x-ui::steps.item label="Project kickoff" />
    </x-ui::steps>
</div>
