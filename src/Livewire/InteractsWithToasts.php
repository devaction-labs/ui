<?php

namespace DevAction\Ui\Livewire;

/**
 * Dispara toasts do kit a partir de um componente Livewire. Precisa do <x-ui::toast.group /> no layout.
 *
 * use InteractsWithToasts;
 *
 * $this->toast('Alterações salvas.', variant: 'success');
 * $this->toast('Gerando relatório…', variant: 'loading', id: 'relatorio');
 * $this->toast('Relatório pronto.', variant: 'success', id: 'relatorio');   substitui o de carregando
 * $this->toast('Tarefa excluída.', action: ['label' => 'Desfazer', 'event' => 'undo-delete']);
 */
trait InteractsWithToasts
{
    /**
     * @param  array{label: string, event?: string, params?: array<string, mixed>}|null  $action
     */
    public function toast(
        string $text,
        ?string $heading = null,
        string $variant = 'default',
        ?int $duration = null,
        ?string $id = null,
        ?array $action = null,
        ?int $progress = null,
    ): void {
        $this->dispatch('ui-toast', ...array_filter([
            'text' => $text,
            'heading' => $heading,
            'variant' => $variant,
            'duration' => $duration,
            'id' => $id,
            'action' => $action,
            'progress' => $progress,
        ], fn (mixed $value): bool => $value !== null));
    }

    public function dismissToast(string $id): void
    {
        $this->dispatch('ui-toast-dismiss', id: $id);
    }
}
