<?php

namespace DevAction\Ui\Livewire;

use Closure;

/**
 * Controle de um modal pelo nome, devolvido por InteractsWithModals::modal().
 */
class ModalHandle
{
    /**
     * @param  Closure(string): mixed  $dispatch
     */
    public function __construct(protected Closure $dispatch) {}

    public function show(): void
    {
        ($this->dispatch)('show');
    }

    public function close(): void
    {
        ($this->dispatch)('close');
    }
}
