{{-- Dropzone: multiple file drop area with a queue showing done, uploading, error, waiting and invalid files — see references/dropzone.md --}}
<div class="flex flex-col gap-4 rounded-lg border border-line bg-card p-4 text-ink">
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
</div>
