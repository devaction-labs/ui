<?php

namespace DevAction\Ui;

use DevAction\Ui\Livewire\ModalHandle;
use Livewire\Livewire;
use RuntimeException;

/**
 * Controle dos modais do kit de qualquer lugar do código PHP que roda dentro de uma requisição do Livewire
 * (componente, action, service), como o Flux::modal().
 *
 * Ui::modal('confirmar')->show();
 * Ui::modal('confirmar')->close();
 * Ui::modals()->close();
 */
class Ui
{
    public static function modal(string $name): ModalHandle
    {
        return new ModalHandle(fn (string $action) => static::dispatch(['name' => $name, 'action' => $action]));
    }

    public static function modals(): ModalHandle
    {
        return new ModalHandle(fn () => static::dispatch(['action' => 'close-all']));
    }

    /**
     * @param  array{name?: string, action: string}  $params
     */
    protected static function dispatch(array $params): void
    {
        $component = class_exists(Livewire::class) ? Livewire::current() : null;

        if (! $component) {
            throw new RuntimeException('Ui::modal() só funciona durante uma requisição do Livewire. No JavaScript, use $modal(\'nome\').show().');
        }

        $component->dispatch('ui-modal', ...$params);
    }
}
