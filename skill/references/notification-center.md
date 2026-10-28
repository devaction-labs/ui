---
kind: component
group: Overlay
components: [notification-center, notification-center.group, notification-center.item]
---
# Notification center · `<x-ui::notification-center>`

Notification center: tabs with unread counts, grouped by day, mark one or all as read. Standalone or in the bell.

## Examples

### In the bell

Inside a dropdown with an unpadded popover. Clicking a notification marks it as read; the tab counts update instantly.

```blade
<x-ui::dropdown align="end">
    <x-ui::button icon="notification-01" aria-label="Notifications" />
    <x-ui::popover class="w-[400px] overflow-hidden p-0">
        <x-ui::notification-center :tabs="['all' => 'All', 'mention' => 'Mentions', 'review' => 'Reviews']">
            <x-ui::notification-center.group heading="Today">
                <x-ui::notification-center.item type="review" icon="sparkles" color="warn" unread meta="sge-api · 4 min ago">The AI finished reviewing #214: 2 blocking findings</x-ui::notification-center.item>
                <x-ui::notification-center.item type="mention" icon="at" color="info" unread meta="SGE · M3 · 1 h ago">Carlos André mentioned you in "Financial reports"</x-ui::notification-center.item>
                <x-ui::notification-center.item type="other" icon="contracts" color="risk" unread meta="Contracts · 3 h ago">AYRAM contract renews in 3 days with no proposal sent</x-ui::notification-center.item>
            </x-ui::notification-center.group>
            <x-ui::notification-center.group heading="Yesterday">
                <x-ui::notification-center.item type="review" icon="checkmark-circle-02" color="ok" meta="hub-api · yesterday 5:40 PM">Hub API #88 approved by the AI, ready to merge</x-ui::notification-center.item>
                <x-ui::notification-center.item type="mention" icon="user-add-01" meta="SGE · M4 · yesterday 10:12 AM">Fernando assigned "Calendar and rosters" to you</x-ui::notification-center.item>
            </x-ui::notification-center.group>
            <x-slot:footer>
                <a href="#">Notification preferences</a>
                <a href="#">See all</a>
            </x-slot:footer>
        </x-ui::notification-center>
    </x-ui::popover>
</x-ui::dropdown>
```

### With Livewire

mark-all calls the mark-all method; wire:click on each item saves the read. The ids become the detail.id of the notification-read event.

```blade
<x-ui::notification-center mark-all="markAllAsRead" class="max-w-md rounded-2xl border border-line">
    <x-ui::notification-center.group heading="Today">
        <x-ui::notification-center.item id="notification-12" icon="sparkles" color="warn" unread meta="4 min ago" wire:click="markAsRead(12)">Review of #214 is ready</x-ui::notification-center.item>
    </x-ui::notification-center.group>
</x-ui::notification-center>
```

```php
public function markAsRead(int $id): void
{
    auth()->user()->notifications()->whereKey($id)->update(['read_at' => now()]);
}

public function markAllAsRead(): void
{
    auth()->user()->unreadNotifications->markAsRead();
}
```

## API

The default heading, tab, empty and "Mark all as read" texts follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::notification-center`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `Notifications` | Heading. |
| `tabs` | array | `['all' => 'All']` | Tabs [key => label]; they filter by the item type. |
| `mark-all` | string | `null` | Livewire mark-all method; fires notifications-read-all. |
| `empty` | string | `Nothing here.` | Text for an empty tab. |
| `slot footer` | slot | `—` | Footer links; the last one goes to the right. |

### `x-ui::notification-center.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `—` | Day or period (TODAY, YESTERDAY…). Hidden when the tab has no items in it. |

### `x-ui::notification-center.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `type` | string | `null` | For the tab filter. |
| `icon · color` | string | `notification-01 · plain` | Icon and color: warn · info · ok · risk · plain. |
| `unread` | boolean | `false` | Amber background, emphasized text and a dot. |
| `meta · href` | string | `null` | Detail line and link. |

## Accessibility

- It is a <section> named by its heading; the tabs are role="tab" with aria-selected.
- Unread is not just color: the text is emphasized and screen readers hear "unread".
- "Mark all as read" is disabled when there is nothing to mark.
