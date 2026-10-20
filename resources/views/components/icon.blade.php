{{--
    Hugeicons · Stroke Rounded (24×24, stroke 1.5, currentColor).
    Cada ícone é um arquivo em icons/{name}.blade.php do pacote (ou resources/views/vendor/ui/icons/ no app) com o miolo do SVG
    exportado do hugeicons.com (só os elementos internos, sem a tag <svg>).

    <x-ui::icon name="sparkles" />
    <x-ui::icon name="git-pull-request" class="size-4 text-muted" />
    <x-ui::icon name="github" label="GitHub" />
--}}
@props([
    'name',
    'label' => null,
    'strokeWidth' => 1.5,
])

<svg
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    @if ($label)
        role="img"
        aria-label="{{ $label }}"
    @else
        aria-hidden="true"
    @endif
    {{ $attributes->class([
        'shrink-0',
        'size-5' => ! preg_match('/(^|\s)size-/', (string) $attributes->get('class')),
    ]) }}
    data-icon="{{ $name }}"
>
    @include('ui::icons.'.$name)
</svg>
