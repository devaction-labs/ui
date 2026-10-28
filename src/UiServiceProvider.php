<?php

namespace DevAction\Ui;

use DevAction\Ui\Console\IconCommand;
use DevAction\Ui\Console\SkillCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Blaze\Blaze;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Registra as views do pacote no namespace "ui", os componentes anônimos como <x-ui::*>
     * e as traduções (textos em inglês; lang/pt_BR.json traz o português). Para trocar um texto,
     * o app repete a chave no próprio lang/{locale}.json, que tem prioridade.
     *
     * Ícones podem ser adicionados ou sobrescritos pelo app em resources/views/vendor/ui/icons.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ui');
        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'ui');

        $this->optimizeWithBlaze();
        $this->registerMcpServer();

        $this->publishes([
            __DIR__.'/../resources/views/icons' => resource_path('views/vendor/ui/icons'),
        ], 'ui-icons');

        if ($this->app->runningInConsole()) {
            $this->commands([IconCommand::class, SkillCommand::class]);
        }
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

    /**
     * Registra o servidor MCP local `devaction-ui` quando o app tem laravel/mcp (opcional).
     *
     * As classes do laravel/mcp ficam só em strings/FQCN: sem o pacote, nada é carregado.
     */
    protected function registerMcpServer(): void
    {
        if (! class_exists('Laravel\Mcp\Facades\Mcp')) {
            return;
        }

        // Por string: o Pint importaria um FQCN, e o provider não pode referenciar classes do laravel/mcp.
        $this->app->make('Laravel\\Mcp\\Server\\Registrar')->local('devaction-ui', 'DevAction\\Ui\\Mcp\\UiServer');
    }
}
