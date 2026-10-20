{{--
    Título e texto de apoio de uma tela de autenticação.

    <x-ui::auth.heading heading="Entrar" description="Bem-vindo de volta." />
--}}
@props([
    'heading',
    'description' => null,
])

<div data-auth-heading {{ $attributes->class(['mb-6 flex flex-col gap-1.5']) }}>
    <h1 class="text-2xl font-semibold tracking-[-0.02em] text-ink">{{ $heading }}</h1>
    @if ($description)
        <p class="text-sm text-ink-3">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>
