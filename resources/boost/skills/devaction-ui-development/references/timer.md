# Timer · `<x-ui::timer>`

Tracks time on a task for the hour bank. When stopped, it reports the seconds so you can log the hours.

## Examples

### Task timer

Green while running. Stop fires timer-stopped with the seconds; in Livewire, call the method that logs the hours.

```blade
<x-ui::timer
    label="Fix N+1 in contributions · SGE"
    :seconds="2710"
    x-on:timer-stopped="$toast({ text: 'Logged: ' + Math.round($event.detail.seconds / 60) + ' min', variant: 'success' })"
    class="w-full max-w-sm"
/>
```

### Mini timer

variant="mini" fits in the app bar or a list row.

```blade
<x-ui::timer variant="mini" label="Mini timer in the app bar" :seconds="1520" running class="w-full max-w-sm" />
```

## API

Store started-at on the server when starting: with running and started-at, the timer resumes the right time after a page reload. Button labels and states (RUNNING, PAUSED) follow the app locale.

### `x-ui::timer`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Task being tracked. |
| `seconds` | int | `0` | Time already tracked. |
| `running` | boolean | `false` | Starts running. |
| `started-at` | int | `null` | Unix timestamp of when it started running. |
| `variant` | card \| mini | `card` | Large card or compact row. |
| `x-on:timer-stopped` | event | `—` | detail.seconds on stop; also timer-started and timer-paused. |

## Accessibility

- The time has role="timer" and is not announced every second.
- Buttons have aria-labels "Start", "Pause" and "Stop and log".
- State is shown in text (RUNNING, PAUSED), not color alone.
- The pulsing dot in mini respects prefers-reduced-motion.
