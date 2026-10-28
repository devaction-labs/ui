{{-- Date picker: single date field with a minimum, a range field and an inline calendar — see references/date-picker.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::date-picker label="Due date" value="2026-10-23" min="2026-10-19" class="max-w-sm" />

    {{-- Variation: date range and an inline calendar --}}
    <x-ui::date-picker label="Milestone period" range :value="['start' => '2026-10-13', 'end' => '2026-10-24']" class="max-w-sm" />

    <x-ui::date-picker inline value="2026-10-16" />
</div>
