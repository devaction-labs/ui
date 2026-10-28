{{-- Switch: on and off toggles in sizes, colors and states, plus labelled settings rows — see references/switch.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="flex flex-wrap items-center gap-6">
        <x-ui::switch aria-label="Off" />
        <x-ui::switch aria-label="On" checked />
        <x-ui::switch aria-label="Disabled" checked disabled />
        <x-ui::switch aria-label="Saving" checked loading />
        <x-ui::switch size="sm" aria-label="Small" checked />
        <x-ui::switch size="lg" aria-label="Large" checked />
        <x-ui::switch color="ai" aria-label="AI" checked />
        <x-ui::switch color="ok" aria-label="Success" checked />
    </div>

    {{-- Variation: settings rows with descriptions, and cards that highlight the checked state --}}
    <div class="flex w-full max-w-lg flex-col divide-y divide-line-soft">
        <x-ui::switch label="Automatic review" description="The AI reviews every PR opened in the watched repositories." class="py-3" checked />
        <x-ui::switch label="Publish as draft" description="Comments only go to GitHub after you approve them." class="py-3" checked />
        <x-ui::switch label="Review Dependabot PRs" description="Dependency updates tend to add noise." class="py-3" />
        <x-ui::switch label="Create lessons from PRs" description="Available on the Agency plan." class="py-3" disabled />
    </div>

    <div class="grid w-full max-w-lg gap-2.5 sm:grid-cols-2">
        <label class="flex items-center gap-3 rounded-[14px] border border-line bg-card p-3.5 has-checked:border-ink has-checked:bg-subtle">
            <span class="flex size-9 items-center justify-center rounded-[10px] bg-wrap"><x-ui::icon name="mail-01" class="size-[18px]" /></span>
            <span class="flex flex-1 flex-col"><span class="text-sm font-medium">Weekly summary</span><span class="text-xs text-muted">Every Monday at 8 am</span></span>
            <x-ui::switch size="sm" aria-label="Weekly summary" checked />
        </label>
        <label class="flex items-center gap-3 rounded-[14px] border border-line bg-card p-3.5 has-checked:border-ink has-checked:bg-subtle">
            <span class="flex size-9 items-center justify-center rounded-[10px] bg-wrap"><x-ui::icon name="moon-02" class="size-[18px]" /></span>
            <span class="flex flex-1 flex-col"><span class="text-sm font-medium">Dark theme</span><span class="text-xs text-muted">Follows the system</span></span>
            <x-ui::switch size="sm" aria-label="Dark theme" />
        </label>
    </div>
</div>
