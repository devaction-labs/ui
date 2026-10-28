{{-- Date range: period picker with presets, and an inline range with time entries — see references/date-range.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::date-range
        label="Period"
        :value="['start' => '2026-10-01', 'end' => '2026-10-15']"
        today="2026-10-15"
        :presets="[['label' => 'SGE milestone M3', 'start' => '2026-10-01', 'end' => '2026-10-31']]"
    />

    {{-- Variation: inline range with time, for a time log entry --}}
    <x-ui::date-range
        inline
        with-time
        time-heading="Time log entry"
        timezone="America/New_York (UTC−4)"
        today="2026-10-15"
        :value="['start' => '2026-10-01', 'end' => '2026-10-15', 'start_time' => '09:00', 'end_time' => '11:15']"
    />
</div>
