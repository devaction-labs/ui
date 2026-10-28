{{-- Kbd: keyboard shortcuts, bordered in a search field and plain in a list — see references/kbd.md --}}
@php
    $shortcuts = [
        ['label' => 'Open command palette', 'keys' => '⌘K'],
        ['label' => 'Close dialog', 'keys' => 'Esc'],
        ['label' => 'Print page', 'keys' => '⇧ ⌘ P'],
    ];
    $menuItems = [
        ['label' => 'Save changes', 'keys' => '⌘ S'],
        ['label' => 'Confirm selection', 'keys' => '↵'],
    ];
@endphp

<div class="flex flex-col gap-6 bg-card p-4 text-ink">
    {{-- Bordered: search field and toolbar hints --}}
    <div class="flex flex-col gap-2">
        <h3 class="text-sm font-medium text-ink">Shortcuts</h3>
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($shortcuts as $shortcut)
                <span class="inline-flex items-center gap-2 text-sm text-muted">
                    <x-ui::kbd>{{ $shortcut['keys'] }}</x-ui::kbd>
                    {{ $shortcut['label'] }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Plain: inside a list or menu --}}
    <ul class="flex flex-col divide-y divide-line border border-line">
        @foreach ($menuItems as $item)
            <li class="flex items-center justify-between px-3 py-2 text-sm text-ink">
                <span>{{ $item['label'] }}</span>
                <x-ui::kbd plain>{{ $item['keys'] }}</x-ui::kbd>
            </li>
        @endforeach
    </ul>

    <a href="#" class="text-sm text-muted underline">Keyboard shortcuts guide</a>
</div>
