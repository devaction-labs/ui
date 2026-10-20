{{--
    Mensagem de erro de um campo. Com name, lê o erro de validação do Laravel/Livewire.

    <x-ui::error name="email" />
    <x-ui::error message="Falta o domínio depois do @." />
--}}
@props([
    'name' => null,
    'message' => null,
])

@php
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $message ??= $name && $errorBag ? $errorBag->first($name) : null;
    $message = $message ?: ($slot->isNotEmpty() ? (string) $slot : null);
@endphp

@if ($message)
    <p role="alert" data-error {{ $attributes->class(['flex items-center gap-1.5 text-[13px] text-risk-fg']) }}>
        <x-ui::icon name="alert-02" class="size-[15px]" />
        {{ $message }}
    </p>
@endif
