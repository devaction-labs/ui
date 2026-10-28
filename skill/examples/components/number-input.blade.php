{{-- Number input: stepped numeric field with min, max, step and unit suffix — see references/number-input.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="grid w-full max-w-xl gap-4 sm:grid-cols-3">
        <x-ui::number-input label="Milestone hours" value="60" min="0" max="200" step="5" unit="h" />
        <x-ui::number-input label="Installments" value="5" min="1" max="12" />
        <x-ui::number-input label="Discount" value="7.5" min="0" max="30" step="0.5" unit="%" />
    </div>

    {{-- Variation: small size, and an error message --}}
    <div class="grid w-full max-w-md gap-4">
        <x-ui::number-input size="sm" value="3" min="0" unit="days" />
        <x-ui::number-input label="Deadline" value="0" min="0" unit="days" error="The deadline must be greater than zero." />
    </div>
</div>
