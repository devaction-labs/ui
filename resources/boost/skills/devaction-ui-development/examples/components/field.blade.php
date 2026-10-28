{{-- Field: label, control, description and error stacked in a column, plus label badges and an inline switch — see references/field.md --}}
@php
    $email = 'ana@example.com';
@endphp

<x-ui::field>
    <x-ui::label for="email">Email</x-ui::label>
    <x-ui::input id="email" type="email" :value="$email" wire:model="email" />
    <x-ui::description>We only use it to send receipts.</x-ui::description>
    <x-ui::error name="email" />
</x-ui::field>

{{-- Variation: label with an optional badge and a shortcut hint, then an inline switch --}}
<div class="mt-8 flex flex-col gap-4">
    <x-ui::label badge="Optional">Phone</x-ui::label>

    <x-ui::label>
        Deadline
        <x-slot:trailing><x-ui::kbd>⌘D</x-ui::kbd></x-slot:trailing>
    </x-ui::label>

    <x-ui::field variant="inline">
        <x-ui::switch wire:model="notify" />
        <x-ui::label>Notify me by email</x-ui::label>
    </x-ui::field>

    <x-ui::error message="The domain after the @ is missing." />
</div>
