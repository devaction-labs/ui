{{--
    Arquivo na fila do dropzone. Só desenha: progresso e status vêm do seu upload.

    <x-ui::dropzone.file name="Aditivo 02.pdf" size="1,8 MB" status="done">
        <x-ui::dropzone.remove wire:click="removeAttachment(1)" />
    </x-ui::dropzone.file>

    status:  done · uploading · error · waiting · invalid
    message: troca o texto do status (ex.: "Conexão caiu", "Tipo não aceito")
    meta:    troca o texto pequeno embaixo (padrão: tamanho e estado)
    O slot recebe as ações (tentar de novo, remover).
--}}
@props([
    'name',
    'size' => null,
    'status' => 'done',
    'progress' => null,
    'message' => null,
    'meta' => null,
])

@php
    $extension = strtoupper(pathinfo($name, PATHINFO_EXTENSION)) ?: 'ARQ';
    $isFailed = in_array($status, ['error', 'invalid'], true);
    $percent = match ($status) {
        'done' => 100,
        'waiting', 'invalid' => 0,
        default => max(0, min(100, (int) $progress)),
    };
    $message ??= match ($status) {
        'done' => 'Enviado',
        'uploading' => $percent.'%',
        'error' => 'Falhou',
        'waiting' => 'Na fila',
        'invalid' => 'Tipo não aceito',
        default => '',
    };
    $meta ??= match ($status) {
        'uploading' => trim('enviando · '.$size, ' ·'),
        'error' => 'parou em '.$percent.'%',
        default => $size,
    };
@endphp

<div
    data-dropzone-file
    data-status="{{ $status }}"
    {{ $attributes->class([
        'flex items-center gap-3 rounded-xl border px-3 py-2.5',
        'border-risk-line bg-risk-bg/40' => $isFailed,
        'border-line bg-card' => ! $isFailed,
    ]) }}
>
    <span @class([
        'flex size-10 flex-none items-center justify-center rounded-[9px] border border-line font-mono text-[10px] font-semibold',
        'bg-risk-bg text-risk-fg' => $status === 'done' && $extension === 'PDF',
        'bg-wrap text-ink-2' => ! ($status === 'done' && $extension === 'PDF') && ! in_array($status, ['waiting', 'invalid'], true),
        'bg-wrap text-muted' => in_array($status, ['waiting', 'invalid'], true),
    ])>{{ $extension }}</span>

    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="truncate font-medium text-ink">{{ $name }}</span>
            <span @class([
                'ml-auto flex-none text-xs',
                'text-ok-fg' => $status === 'done',
                'text-ink' => $status === 'uploading',
                'text-risk-fg' => $isFailed,
                'text-muted' => $status === 'waiting',
            ])>{{ $message }}</span>
        </div>
        <div class="mt-1.5 flex items-center gap-2.5">
            <span class="h-1 flex-1 overflow-hidden rounded-sm bg-line-soft" role="progressbar" aria-label="Progresso de {{ $name }}" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                <span @class([
                    'block h-full',
                    'bg-ok-fg' => $status === 'done',
                    'bg-risk-fg' => $isFailed,
                    'bg-ink' => ! $isFailed && $status !== 'done',
                ]) style="width: {{ $percent }}%"></span>
            </span>
            <span class="min-w-[92px] flex-none text-right font-mono text-[11px] text-muted">{{ $meta }}</span>
        </div>
    </div>

    {{ $slot }}
</div>
