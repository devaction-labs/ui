{{-- Notification center: tabs with unread counts, grouped by day, mark one or all as read — see references/notification-center.md --}}
@php
    $tabs = ['all' => 'All', 'mention' => 'Mentions', 'review' => 'Reviews'];
    $today = [
        ['type' => 'review', 'icon' => 'sparkles', 'color' => 'warn', 'unread' => true, 'meta' => 'sge-api · 4 min ago', 'text' => 'The AI finished reviewing #214: 2 blocking findings'],
        ['type' => 'mention', 'icon' => 'at', 'color' => 'info', 'unread' => true, 'meta' => 'SGE · M3 · 1 h ago', 'text' => 'Carlos André mentioned you in "Financial reports"'],
    ];
@endphp

{{-- Main: the notification center opened from a bell button --}}
<x-ui::dropdown align="end">
    <x-ui::button icon="notification-01" aria-label="Notifications" />
    <x-ui::popover class="w-[400px] overflow-hidden p-0">
        <x-ui::notification-center :tabs="$tabs">
            <x-ui::notification-center.group heading="Today">
                @foreach ($today as $n)
                    <x-ui::notification-center.item
                        :type="$n['type']"
                        :icon="$n['icon']"
                        :color="$n['color']"
                        :unread="$n['unread']"
                        :meta="$n['meta']"
                    >{{ $n['text'] }}</x-ui::notification-center.item>
                @endforeach
            </x-ui::notification-center.group>
            <x-slot:footer>
                <a href="#">Notification preferences</a>
                <a href="#">See all</a>
            </x-slot:footer>
        </x-ui::notification-center>
    </x-ui::popover>
</x-ui::dropdown>

{{-- Variation: standalone with Livewire mark-all and a per-item wire:click --}}
<x-ui::notification-center mark-all="markAllAsRead" class="max-w-md rounded-2xl border border-line">
    <x-ui::notification-center.group heading="Today">
        <x-ui::notification-center.item id="notification-12" icon="sparkles" color="warn" unread meta="4 min ago" wire:click="markAsRead(12)">
            Review of #214 is ready
        </x-ui::notification-center.item>
    </x-ui::notification-center.group>
</x-ui::notification-center>
