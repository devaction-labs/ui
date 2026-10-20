{{--
    Dropzone: arraste arquivos ou clique para escolher. Usa um input file nativo, então
    wire:model funciona com os uploads do Livewire (progresso aparece sozinho).

    <x-ui::dropzone wire:model="attachments" multiple accept=".pdf,.png,.jpg" text="PDF, PNG ou JPG · até 25 MB cada" />

    <x-ui::dropzone.file name="Aditivo 02.pdf" size="1,8 MB" status="done" />
    <x-ui::dropzone.file name="Relatório M3.pdf" size="6,1 MB" status="uploading" :progress="64" />

    heading: texto principal · text: tipos e limites
    Atributos (wire:model, name, accept, multiple, disabled) vão para o input file.
--}}
@props([
    'label' => null,
    'heading' => 'Arraste arquivos ou clique para escolher',
    'text' => null,
    'error' => null,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $attributes->get('name') ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? ($errorBag->first($fieldName) ?: $errorBag->first($fieldName.'.*')) : null);
    $inputId = $attributes->get('id') ?? 'ui-dropzone-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($heading.$text), 0, 8));
    $isDisabled = (bool) $attributes->get('disabled');
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
    @endif

    <label
        data-dropzone
        for="{{ $inputId }}"
        x-data="uiDropzone"
        x-on:dragenter.prevent="enter($event)"
        x-on:dragover.prevent
        x-on:dragleave.prevent="leave()"
        x-on:drop.prevent="drop($event)"
        x-on:livewire-upload-start="uploading = true; progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-error="uploading = false"
        x-on:livewire-upload-cancel="uploading = false"
        @class([
            'relative flex h-[170px] w-full flex-col items-center justify-center gap-2 rounded-[14px] border-[1.5px] border-dashed bg-card px-4 text-center transition-colors has-focus-visible:ring-4 has-focus-visible:ring-ink/10',
            'cursor-pointer hover:border-line-hover' => ! $isDisabled,
            'cursor-not-allowed opacity-60' => $isDisabled,
            'border-missing' => $errorMessage,
            'border-line-hover' => ! $errorMessage,
        ])
        x-bind:class="dragging && 'border-ai! bg-ai-soft!'"
    >
        <input
            id="{{ $inputId }}"
            x-ref="input"
            type="file"
            x-on:change="chosen()"
            @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-hint" @endif
            {{ $attributes->except(['id', 'class'])->class(['sr-only']) }}
        />
        <span
            class="flex size-11 items-center justify-center rounded-xl border border-line bg-card text-ink transition-colors"
            x-bind:class="dragging && 'border-ai! bg-ai! text-white!'"
            aria-hidden="true"
        >
            <x-ui::icon name="cloud-upload" class="size-5" />
        </span>
        <span class="font-medium text-ink" x-text="dragging ? 'Solte para enviar' : @js($heading)">{{ $heading }}</span>
        <span class="text-[13px] text-muted" x-show="dragging || summary" x-text="summary" x-cloak></span>
        @if ($text)
            <span class="text-[13px] text-muted" x-show="! dragging && ! summary">{{ $text }}</span>
        @endif
        <span x-show="uploading" x-cloak class="absolute inset-x-4 bottom-3 h-1 overflow-hidden rounded-sm bg-line-soft" role="progressbar" aria-label="Enviando" x-bind:aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
            <span class="block h-full bg-ink transition-[width]" x-bind:style="{ width: progress + '%' }"></span>
        </span>
    </label>

    @if ($errorMessage)
        <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
    @endif

    @if ($slot->isNotEmpty())
        <div class="mt-2.5 flex flex-col gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
