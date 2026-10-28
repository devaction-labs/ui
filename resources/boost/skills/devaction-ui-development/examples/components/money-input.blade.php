{{-- Money input: currency-aware amount field with a suffix, description and error — see references/money-input.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="grid w-full max-w-md gap-4">
        <x-ui::money-input label="Milestone amount" currency="USD" locale="en-US" value="18500" suffix="per milestone" />
        <x-ui::money-input label="Hourly rate" currency="USD" locale="en-US" value="220" suffix="per hour" description="Used in the hour bank." />
    </div>

    {{-- Variation: other locales and an error message --}}
    <div class="grid w-full max-w-md gap-4">
        <x-ui::money-input label="Annual license" currency="EUR" locale="de-DE" value="1250.5" />
        <x-ui::money-input label="Discount" currency="BRL" locale="pt-BR" error="The discount cannot exceed the contract amount." />
    </div>
</div>
