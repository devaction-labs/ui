<?php

namespace DevAction\Ui\Support;

/**
 * Espelho da pasta skill/ em resources/boost/skills/devaction-ui-development/ (onde o Laravel Boost procura).
 */
class SkillMirror
{
    public const MIRROR = 'resources/boost/skills/devaction-ui-development';

    private readonly string $root;

    public function __construct(?string $root = null)
    {
        $this->root = rtrim($root ?? dirname(__DIR__, 2), '/');
    }

    /**
     * Arquivos que divergem entre skill/ e o espelho, com o motivo.
     *
     * @return list<string>
     */
    public function diff(): array
    {
        $source = $this->files($this->root.'/skill');
        $mirror = $this->files($this->root.'/'.self::MIRROR);
        $problems = [];

        foreach ($source as $relative => $path) {
            if (! isset($mirror[$relative])) {
                $problems[] = self::MIRROR.'/'.$relative.' (faltando)';
            } elseif (file_get_contents($path) !== file_get_contents($mirror[$relative])) {
                $problems[] = self::MIRROR.'/'.$relative.' (diferente)';
            }
        }

        foreach (array_diff_key($mirror, $source) as $relative => $path) {
            $problems[] = self::MIRROR.'/'.$relative.' (sobrando)';
        }

        sort($problems);

        return $problems;
    }

    /**
     * Copia skill/ para o espelho e remove sobras, sempre e só dentro do espelho.
     */
    public function sync(): void
    {
        $target = $this->root.'/'.self::MIRROR;
        $source = $this->files($this->root.'/skill');

        foreach (array_diff_key($this->files($target), $source) as $path) {
            if (! @unlink($path)) {
                throw new \RuntimeException('Falha ao remover '.$path);
            }
        }

        foreach ($source as $relative => $path) {
            $directory = dirname($target.'/'.$relative);

            if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
                throw new \RuntimeException('Falha ao criar '.$directory);
            }

            if (! @copy($path, $target.'/'.$relative)) {
                throw new \RuntimeException('Falha ao copiar para '.$target.'/'.$relative);
            }
        }

        $this->pruneEmptyDirectories($target);
    }

    /**
     * Arquivos de uma pasta, indexados pelo caminho relativo.
     *
     * @return array<string, string>
     */
    private function files(string $directory): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            if ($file->isFile() && ! $file->isLink()) {
                $files[substr((string) $file, strlen($directory) + 1)] = (string) $file;
            }
        }

        ksort($files);

        return $files;
    }

    private function pruneEmptyDirectories(string $directory): void
    {
        $base = realpath($this->root.'/'.self::MIRROR);

        if ($base === false || is_link($directory) || ! is_dir($directory)) {
            return;
        }

        foreach (scandir($directory) ?: [] as $entry) {
            $path = $directory.'/'.$entry;

            if ($entry === '.' || $entry === '..' || is_link($path) || ! is_dir($path)) {
                continue;
            }

            $this->pruneEmptyDirectories($path);

            $real = realpath($path);

            if ($real !== false && str_starts_with($real, $base.'/') && (scandir($path) ?: []) === ['.', '..'] && ! rmdir($path)) {
                throw new \RuntimeException('Falha ao remover '.$path);
            }
        }
    }
}
