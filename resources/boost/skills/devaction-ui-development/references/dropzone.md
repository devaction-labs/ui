---
kind: component
group: Form
components: [dropzone, dropzone.file, dropzone.remove]
---
# Dropzone · `<x-ui::dropzone>`

Area to drag files in or click to choose, with the upload queue below. It uses the native file input, so Livewire uploads work without configuration.

## Examples

### Contract attachments

Drag files over the area: it turns amber and shows how many there are. The queue shows each state: uploaded, uploading, failed, queued and type not accepted.

```blade
<x-ui::dropzone multiple accept=".pdf,.png,.jpg" text="PDF, PNG or JPG · up to 25 MB each · 10 max" class="w-full max-w-xl">
    <x-ui::dropzone.file name="SGE contract · Amendment 02.pdf" size="1.8 MB" status="done">
        <x-ui::dropzone.remove icon="delete-02" />
    </x-ui::dropzone.file>
    <x-ui::dropzone.file name="M3 reports specification.pdf" size="6.1 MB" status="uploading" :progress="64">
        <x-ui::dropzone.remove />
    </x-ui::dropzone.file>
    <x-ui::dropzone.file name="finance-screenshot.png" status="error" :progress="38" message="Connection dropped">
        <x-ui::button size="sm" icon="refresh">Try again</x-ui::button>
        <x-ui::dropzone.remove />
    </x-ui::dropzone.file>
    <x-ui::dropzone.file name="Meeting notes 10-14.pdf" size="420 KB" status="waiting">
        <x-ui::dropzone.remove />
    </x-ui::dropzone.file>
    <x-ui::dropzone.file name="timesheet.xlsx" status="invalid" meta="use PDF, PNG or JPG">
        <x-ui::dropzone.remove />
    </x-ui::dropzone.file>
</x-ui::dropzone>
```

## API

wire:model, name, accept, multiple and disabled go to the file input. With Livewire (WithFileUploads), the progress bar shows up by itself while uploading; build the queue with x-ui::dropzone.file from your temporary files. The default texts follow the app locale.

### `x-ui::dropzone`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Label above the area. |
| `heading` | string | `Drag files here…` | Main text. |
| `text` | string | `null` | Accepted types and limits. |
| `error` | string | `null` | Error; with wire:model it comes from validation (including field.*). |

### `x-ui::dropzone.file`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name · size` | string | `—` | File name (the extension becomes the thumbnail) and size. |
| `status` | done \| uploading \| error \| waiting \| invalid | `done` | State in the queue. |
| `progress` | int | `null` | Percentage uploaded. |
| `message · meta` | string | `from status` | Status text and the line below. |

### `x-ui::dropzone.remove`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `icon · label` | string | `cancel-01 · Remove` | Icon and accessible name. |

## Accessibility

- The area is the label of a native file input: Tab focuses it, Enter or Space open the picker.
- Dragging is optional; clicking does the same.
- Each file has a progressbar with its name; the status is shown as text, not only by color.
- Remove buttons have an aria-label.
