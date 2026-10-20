# Hover card · `<x-ui::hover-card>`

Card that opens on hover or focus: person, PR, company. Shows context without taking people off the page.

## Examples

### Person and PR

Opens after 400 ms of hover, or right away with the keyboard (Tab). You can move the mouse onto the card; Esc closes.

```blade
<p class="max-w-md text-[15px] leading-[1.7]">
    <x-ui::hover-card>
        <a href="#" class="font-medium underline decoration-dotted underline-offset-4">Alex</a>
        <x-slot:card>
            <x-ui::hover-card.person name="Alex" description="Dev · DevAction Labs" online :stats="['open PRs' => 4, 'this week' => '32 h', 'projects' => 2]" />
        </x-slot:card>
    </x-ui::hover-card>
    requested a review of
    <x-ui::hover-card align="center">
        <a href="#" class="font-mono text-sm font-medium underline decoration-dotted underline-offset-4">#214</a>
        <x-slot:card class="w-[320px]">
            <span class="flex items-center gap-2">
                <x-ui::icon name="git-pull-request" class="size-4 text-ok-fg" />
                <span class="font-mono text-xs text-muted">sge-api #214</span>
                <x-ui::tag color="risk" size="sm" :uppercase="false" class="ml-auto">2 blocking</x-ui::tag>
            </span>
            <span class="mt-2 block font-semibold">Migrate old milestones with due_on</span>
            <span class="mt-1.5 block text-[13px] text-ink-3">7 files · <span class="text-ok-fg">+166</span> <span class="text-risk-fg">−68</span> · M3</span>
        </x-slot:card>
    </x-ui::hover-card>
    in the SGE project.
</p>
```

## API

### `x-ui::hover-card`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `position` | string | `bottom` | bottom · top · right · left (flips by itself if it does not fit). |
| `align` | string | `start` | start · center · end. |
| `delay` | int | `400` | Milliseconds of hover before opening. |
| `slot card` | slot | `—` | Card content; class changes the width (default 300 px). |

### `x-ui::hover-card.person`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name · description · src` | string | `null` | Name, role and photo. |
| `online` | boolean | `null` | Presence dot (green online, gray offline). |
| `stats` | array | `[]` | Up to 3 numbers: ["open PRs" => 4]. |

## Accessibility

- Opens on keyboard focus, not only on hover, and closes with Esc.
- The trigger gets aria-describedby pointing to the card.
- Do not put a required action in the card: it does not open on phones.
