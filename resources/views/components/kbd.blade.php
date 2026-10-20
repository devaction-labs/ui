{{--
    Atalho de teclado.

    <x-ui::kbd>⌘K</x-ui::kbd>
    <x-ui::kbd plain>↵</x-ui::kbd>
--}}
@props([
    'plain' => false,
])

<kbd {{ $attributes->class([
    'inline-flex items-center font-mono text-[11px] leading-none text-muted',
    'rounded-[5px] border border-line bg-wrap px-1.5 py-[3px] text-ink-3' => ! $plain,
]) }}>{{ $slot }}</kbd>
