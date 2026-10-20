{{--
    Diálogo destrutivo: só libera a ação depois que a pessoa digita o nome do recurso.

    <x-ui::modal.trigger name="parar-monitorar">
        <x-ui::button variant="danger">Parar de monitorar</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::danger-dialog
        name="parar-monitorar"
        heading="Parar de monitorar sge-api?"
        text="O review automático para neste repositório."
        resource="sge-api"
        confirm="Parar de monitorar"
        confirm:wire:click="stopMonitoring"
    />

    resource: texto que precisa ser digitado; sem ele, vira uma confirmação vermelha simples
    prompt:   frase acima do campo; :resource é trocado pelo nome em destaque
    confirm:* atributos repassados ao botão de confirmar
--}}
@props([
    'name' => null,
    'heading' => null,
    'text' => null,
    'icon' => 'alert-diamond',
    'resource' => null,
    'prompt' => 'Digite :resource para confirmar',
    'confirm' => 'Excluir',
    'cancel' => 'Cancelar',
    'size' => 'sm',
    'closeOnConfirm' => true,
])

@php
    $confirmAttributes = new \Illuminate\View\ComponentAttributeBag(
        collect($attributes->getAttributes())
            ->filter(fn ($value, string $key): bool => str_starts_with($key, 'confirm:'))
            ->mapWithKeys(fn ($value, string $key): array => [substr($key, strlen('confirm:')) => $value])
            ->all()
    );
    $modalAttributes = $attributes->filter(fn ($value, string $key): bool => ! str_starts_with($key, 'confirm:'));

    if ($resource !== null) {
        $confirmAttributes = $confirmAttributes->merge([
            'disabled' => true,
            'x-bind:disabled' => 'typed !== resource',
        ]);
    }

    // O estado fica no painel do modal, que envolve o corpo e o rodapé com o botão.
    $modalAttributes = $modalAttributes->merge([
        'x-data' => '{ typed: \'\', resource: '.\Illuminate\Support\Js::from($resource).' }',
        'x-init' => "\$watch('open', (isOpen) => { if (! isOpen) typed = '' })",
    ]);

    [$promptBefore, $promptAfter] = array_pad(explode(':resource', $prompt, 2), 2, '');
@endphp

<x-ui::modal :name="$name" :size="$size" :closable="false" :attributes="$modalAttributes">
    <div data-danger-dialog class="flex flex-col gap-4">
        <div class="flex gap-4">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-risk-line bg-risk-bg text-risk-fg">
                <x-ui::icon :name="$icon" />
            </span>
            <div class="flex min-w-0 flex-col gap-1.5">
                @if ($heading)
                    <h2 class="text-lg font-semibold">{{ $heading }}</h2>
                @endif
                @if ($text)
                    <p class="text-sm leading-[1.55] text-ink-3">{{ $text }}</p>
                @endif
                {{ $slot }}
            </div>
        </div>

        @if ($resource !== null)
            <label class="flex flex-col gap-1.5 text-sm text-ink-2">
                <span>{{ $promptBefore }}<code class="rounded-[5px] bg-wrap px-1.5 py-px font-mono text-ink">{{ $resource }}</code>{{ $promptAfter }}</span>
                <input
                    type="text"
                    x-model="typed"
                    autocomplete="off"
                    autocapitalize="off"
                    spellcheck="false"
                    x-bind:aria-invalid="(typed !== '' && typed !== resource).toString()"
                    class="h-11 rounded-xl border border-line-strong bg-card px-3.5 font-mono text-sm text-ink outline-none transition focus:border-ink focus:ring-4 focus:ring-ink/10 aria-invalid:border-risk-fg/50 aria-invalid:focus:ring-risk-fg/10"
                />
            </label>
        @endif
    </div>

    <x-slot:footer>
        <x-ui::modal.close>
            <x-ui::button>{{ $cancel }}</x-ui::button>
        </x-ui::modal.close>

        @if ($closeOnConfirm)
            <x-ui::modal.close>
                <x-ui::button variant="destructive" :attributes="$confirmAttributes">{{ $confirm }}</x-ui::button>
            </x-ui::modal.close>
        @else
            <x-ui::button variant="destructive" :attributes="$confirmAttributes">{{ $confirm }}</x-ui::button>
        @endif
    </x-slot:footer>
</x-ui::modal>
