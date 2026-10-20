<?php

namespace DevAction\Ui\Livewire;

/**
 * Abre e fecha modais do kit a partir de um componente Livewire.
 *
 * use InteractsWithModals;
 *
 * $this->modal('confirmar')->show();
 * $this->modal('confirmar')->close();
 * $this->closeModals();
 */
trait InteractsWithModals
{
    public function modal(string $name): ModalHandle
    {
        return new ModalHandle(fn (string $action) => $this->dispatch('ui-modal', name: $name, action: $action));
    }

    public function closeModals(): void
    {
        $this->dispatch('ui-modal', action: 'close-all');
    }
}
