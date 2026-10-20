{{--
    Texto do callout.

    <x-ui::callout.text>3 itens ainda sem PR no M3.</x-ui::callout.text>
--}}
@aware([
    'stacked' => false,
])

<div {{ $attributes->class(['leading-normal', 'text-ink-2' => $stacked, 'opacity-90' => ! $stacked]) }}>{{ $slot }}</div>
