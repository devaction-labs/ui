{{-- Segmented control: single choice among a few options, with icons, counts and sizes — see references/segmented.md --}}
<div class="flex w-full max-w-md flex-col items-start gap-4 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::segmented label="Agent mode">
        <x-ui::segmented.option value="review" icon="git-pull-request" checked>Review</x-ui::segmented.option>
        <x-ui::segmented.option value="plan" icon="task-01">Plan</x-ui::segmented.option>
        <x-ui::segmented.option value="learn" icon="book-02">Learn</x-ui::segmented.option>
    </x-ui::segmented>

    {{-- Variation: icon-only options with aria-label, a full-width control and counts --}}
    <x-ui::segmented label="View">
        <x-ui::segmented.option value="list" icon="menu-01" aria-label="List" checked />
        <x-ui::segmented.option value="board" icon="dashboard-square-01" aria-label="Board" />
        <x-ui::segmented.option value="calendar" icon="calendar-03" aria-label="Calendar" />
    </x-ui::segmented>

    <x-ui::segmented label="Theme" full>
        <x-ui::segmented.option value="light" icon="sun-03" checked>Light</x-ui::segmented.option>
        <x-ui::segmented.option value="dark" icon="moon-02">Dark</x-ui::segmented.option>
        <x-ui::segmented.option value="system" icon="computer">System</x-ui::segmented.option>
    </x-ui::segmented>

    <x-ui::segmented label="Period" size="sm">
        <x-ui::segmented.option value="week" count="12" checked>Week</x-ui::segmented.option>
        <x-ui::segmented.option value="month" count="48">Month</x-ui::segmented.option>
        <x-ui::segmented.option value="all" count="310">All</x-ui::segmented.option>
    </x-ui::segmented>
</div>
