<?php

namespace DevAction\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Blaze\Blaze;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Registra as views do pacote no namespace "ui" e os componentes anônimos como <x-ui::*>.
     *
     * Ícones podem ser adicionados ou sobrescritos pelo app em resources/views/vendor/ui/icons.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ui');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'ui');

        $this->optimizeWithBlaze();

        $this->publishes([
            __DIR__.'/../resources/views/icons' => resource_path('views/vendor/ui/icons'),
        ], 'ui-icons');
    }

    /**
     * Compila os componentes com o Blaze quando ele está instalado no app.
     *
     * O ícone é memoizado: não tem slot nem estado global e aparece dezenas de vezes por página.
     */
    protected function optimizeWithBlaze(): void
    {
        if (! class_exists(Blaze::class)) {
            return;
        }

        Blaze::optimize()
            ->in(__DIR__.'/../resources/views/components')
            ->in(__DIR__.'/../resources/views/components/icon.blade.php', memo: true);
    }
}
