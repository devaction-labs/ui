{{-- Progress: labeled bars and a stacked bar showing how much is done — see references/progress.md --}}
@php
    $bars = [
        ['label' => 'M3 scope', 'value' => 69, 'max' => 100, 'color' => 'ink', 'valueLabel' => null, 'description' => '18 of 26 items done'],
        ['label' => 'Hour bank · October', 'value' => 62, 'max' => 80, 'color' => 'amber', 'valueLabel' => '62 h of 80 h', 'description' => '18 h left · closes Oct 31'],
    ];
@endphp

<div class="flex w-full max-w-2xl flex-col gap-6 rounded-lg border border-line bg-card p-5">
    <div class="grid gap-x-7 gap-y-5 sm:grid-cols-2">
        @foreach ($bars as $bar)
            <x-ui::progress
                :value="$bar['value']"
                :max="$bar['max']"
                :color="$bar['color']"
                :label="$bar['label']"
                :value-label="$bar['valueLabel']"
                :description="$bar['description']"
                show-value
            />
        @endforeach
    </div>

    <x-ui::progress value="3" max="5" size="lg" label="SGE contract · billed" value-label="3 of 5 installments" description="Paid: 2 · issued: 1 · to bill: 2">
        <x-ui::progress.segment value="1" color="green" />
        <x-ui::progress.segment value="1" color="green" />
        <x-ui::progress.segment value="1" color="amber" />
    </x-ui::progress>
</div>
