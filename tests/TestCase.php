<?php

namespace DevAction\Ui\Tests;

use DevAction\Ui\UiServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Laravel mínimo (Testbench) com o kit e o Livewire registrados, para renderizar os componentes.
 */
abstract class TestCase extends Orchestra
{
    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            UiServiceProvider::class,
        ];
    }

    /**
     * Os testes conferem os textos em português; TranslationsTest cobre o inglês.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.locale', 'pt_BR');
    }
}
