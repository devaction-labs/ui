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
 * $this->progressToast('importacao', 40, 'Importando contatos…', heading: 'Importação');
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

    /**
     * Cria ou avança um toast de progresso. Em 100 ele mostra o check e some sozinho.
     */
    public function progressToast(string $id, int $value, string $text, ?string $heading = null): void
    {
        $this->toast($text, heading: $heading, duration: 0, id: $id, progress: $value);
    }

    public function dismissToast(string $id): void
    {
        $this->dispatch('ui-toast-dismiss', id: $id);
    }
}
