{{-- Stepper: multi-step progress with descriptions, plus an error state with a retry action — see references/stepper.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::stepper :current="2" class="w-full" :steps="[
        ['label' => 'Client', 'description' => 'who is hiring'],
        ['label' => 'Scope', 'description' => 'milestones and deliverables'],
        ['label' => 'Pricing', 'description' => 'installments and due dates'],
        ['label' => 'Review', 'description' => 'send for signature'],
    ]" />

    {{-- Variation: error on the last step, with navigation actions below --}}
    <x-ui::stepper :current="3" error class="w-full" :steps="[
        ['label' => 'Client'],
        ['label' => 'Scope'],
        ['label' => 'Pricing'],
        ['label' => 'Review'],
    ]">
        <div class="mt-2 flex justify-between gap-2.5">
            <x-ui::button>Back</x-ui::button>
            <x-ui::button variant="primary">Try again</x-ui::button>
        </div>
    </x-ui::stepper>
</div>
