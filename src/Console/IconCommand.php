<?php

namespace DevAction\Ui\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Baixa ícones Hugeicons Stroke Rounded (pacote gratuito @hugeicons/core-free-icons) para o app.
 *
 *   php artisan ui:icon calendar-add-01 user-star-01
 *
 * Cada ícone vira resources/views/vendor/ui/icons/{nome}.blade.php e passa a funcionar em <x-ui::icon name="…" />.
 */
class IconCommand extends Command
{
    /**
     * Versão do pacote no npm de onde os ícones são baixados.
     */
    public const HUGEICONS_VERSION = '4.3.5';

    /**
     * Atributos que o <svg> do <x-ui::icon> já define e que não precisam ir para cada elemento.
     *
     * @var array<string, string>
     */
    protected const INHERITED = [
        'stroke' => 'currentColor',
        'strokeLinecap' => 'round',
        'strokeLinejoin' => 'round',
        'strokeWidth' => '1.5',
    ];

    protected $signature = 'ui:icon
        {icons?* : Nomes dos ícones em kebab-case, como em hugeicons.com (ex.: calendar-add-01)}
        {--path= : Pasta de destino (padrão: resources/views/vendor/ui/icons)}
        {--force : Sobrescreve ícones que já existem}';

    protected $description = 'Baixa ícones Hugeicons Stroke Rounded para usar com <x-ui::icon>';

    public function handle(Filesystem $files): int
    {
        $icons = $this->argument('icons') ?: preg_split('/[\s,]+/', (string) $this->ask('Quais ícones? (separe por espaço, ex.: calendar-add-01 user-star-01)'), flags: PREG_SPLIT_NO_EMPTY);

        if ($icons === []) {
            $this->components->error('Informe pelo menos um ícone.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        $directory = match (true) {
            blank($path) => resource_path('views/vendor/ui/icons'),
            str_starts_with($path, '/') => $path,
            default => base_path($path),
        };
        $files->ensureDirectoryExists($directory);

        $failed = 0;

        foreach (array_unique(array_map($this->normalizeName(...), $icons)) as $name) {
            $target = "{$directory}/{$name}.blade.php";

            if ($files->exists($target) && ! $this->option('force')) {
                $this->components->twoColumnDetail($name, '<fg=yellow>já existe (use --force)</>');

                continue;
            }

            $markup = $this->download($name);

            if ($markup === null) {
                $this->components->twoColumnDetail($name, '<fg=red>não encontrado no Hugeicons Stroke Rounded</>');
                $failed++;

                continue;
            }

            $files->put($target, $markup."\n");
            $this->components->twoColumnDetail($name, '<fg=green>ok</>');
        }

        $this->newLine();
        $this->components->info('Use com <x-ui::icon name="'.$this->normalizeName($icons[0]).'" />. Lista completa em hugeicons.com/icons?style=Stroke&type=Rounded');

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Aceita "calendar-add-01", "CalendarAdd01" ou "CalendarAdd01Icon".
     */
    public function normalizeName(string $name): string
    {
        $name = preg_replace('/Icon$/', '', trim($name));

        return Str::of($name)
            ->replaceMatches('/([a-z])([A-Z0-9])/', '$1-$2')
            ->replaceMatches('/([0-9])([A-Z])/', '$1-$2')
            ->lower()
            ->replaceMatches('/[^a-z0-9-]+/', '-')
            ->trim('-')
            ->value();
    }

    /**
     * Baixa o módulo do ícone e devolve os elementos internos do SVG, ou null se ele não existir.
     */
    protected function download(string $name): ?string
    {
        $module = Str::of($name)->explode('-')->map(fn (string $part): string => ucfirst($part))->implode('').'Icon';

        $response = Http::timeout(15)->get(sprintf('https://cdn.jsdelivr.net/npm/@hugeicons/core-free-icons@%s/dist/esm/%s.js', self::HUGEICONS_VERSION, $module));

        if (! $response->successful()) {
            return null;
        }

        return $this->toMarkup($response->body());
    }

    /**
     * Converte [["path", { d: "…", strokeWidth: "1.5" }], …] em <path d="…" />.
     */
    public function toMarkup(string $module): ?string
    {
        preg_match_all('/\[\s*"([a-z]+)",\s*\{([^}]*)\}\s*\]/', $module, $elements, PREG_SET_ORDER);

        if ($elements === []) {
            return null;
        }

        return collect($elements)->map(function (array $element): string {
            preg_match_all('/(\w+):\s*"([^"]*)"/', $element[2], $pairs, PREG_SET_ORDER);

            $attributes = collect($pairs)
                ->reject(fn (array $pair): bool => $pair[1] === 'key' || (self::INHERITED[$pair[1]] ?? null) === $pair[2])
                ->map(fn (array $pair): string => Str::kebab($pair[1]).'="'.e($pair[2]).'"')
                ->implode(' ');

            return "<{$element[1]} {$attributes} />";
        })->implode("\n");
    }
}
