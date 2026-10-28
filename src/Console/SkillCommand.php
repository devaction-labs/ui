<?php

namespace DevAction\Ui\Console;

use DevAction\Ui\Support\ComponentManifest;
use DevAction\Ui\Support\SkillMirror;
use Illuminate\Console\Command;

/**
 * Gera resources/ui-manifest.json e o espelho da skill para o Laravel Boost.
 *
 *   php artisan ui:skill          grava o manifesto e sincroniza o espelho
 *   php artisan ui:skill --check  não grava; sai com 1 se algo estiver fora de sincronia
 */
class SkillCommand extends Command
{
    protected $signature = 'ui:skill
        {--check : Só confere: não grava e falha se o manifesto ou o espelho divergirem}';

    protected $description = 'Gera o manifesto dos componentes e o espelho da skill para o Laravel Boost';

    public function handle(ComponentManifest $manifest, SkillMirror $mirror): int
    {
        $json = $manifest->json();
        $current = is_file($manifest->path()) ? file_get_contents($manifest->path()) : false;

        if ($this->option('check')) {
            $problems = $mirror->diff();

            if ($current !== $json) {
                array_unshift($problems, ComponentManifest::FILE.($current === false ? ' (faltando)' : ' (diferente)'));
            }

            if ($problems === []) {
                $this->components->info('Manifesto e espelho da skill estão em sincronia.');

                return self::SUCCESS;
            }

            $this->components->error('Fora de sincronia. Rode "php artisan ui:skill" para regerar:');

            foreach ($problems as $problem) {
                $this->line("  {$problem}");
            }

            return self::FAILURE;
        }

        if ($current !== $json) {
            file_put_contents($manifest->path(), $json);
            ComponentManifest::flush();
        }

        $mirror->sync();
        $this->components->info(sprintf('Manifesto gravado em %s (%d componentes) e espelho sincronizado.', ComponentManifest::FILE, count($manifest->build()['components'])));

        return self::SUCCESS;
    }
}
