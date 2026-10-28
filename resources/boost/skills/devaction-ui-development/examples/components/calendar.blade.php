{{-- Calendar: month view with colored events and a legend, plus a week view with hours — see references/calendar.md --}}
@php
    $events = [
        ['date' => '2026-10-01', 'title' => 'Kickoff · cycle 10', 'color' => 'blue'],
        ['date' => '2026-10-13', 'title' => 'Daily standup', 'color' => 'teal', 'start' => '09:00', 'end' => '09:30', 'subtitle' => 'Carlos, Fernando'],
        ['date' => '2026-10-13', 'title' => 'PR #142', 'color' => 'blue', 'start' => '11:00', 'end' => '12:30', 'subtitle' => 'review'],
        ['date' => '2026-10-15', 'title' => 'Amendment 01', 'color' => 'amber', 'start' => '13:00', 'end' => '14:00', 'subtitle' => 'send'],
        ['date' => '2026-10-16', 'title' => 'M3 delivery', 'color' => 'blue', 'start' => '09:00', 'end' => '11:00'],
        ['date' => '2026-10-18', 'title' => 'Renewal', 'color' => 'amber'],
        ['date' => '2026-10-31', 'title' => 'Hours closing', 'color' => 'amber'],
    ];
    $legend = ['blue' => 'Milestone delivery', 'amber' => 'Contract', 'teal' => 'Meeting'];
@endphp

<x-ui::calendar month="2026-10" today="2026-10-15" selected="2026-10-16" class="w-full"
    :events="$events"
    :legend="$legend" />

{{-- Variation: week view limited to working hours --}}
<x-ui::calendar month="2026-10" view="week" today="2026-10-15" class="mt-8 w-full" :hours="[8, 17]"
    :events="$events" />
