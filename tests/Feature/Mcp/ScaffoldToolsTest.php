<?php

use DevAction\Ui\Mcp\Scaffolder;
use DevAction\Ui\Mcp\Tools\ScaffoldComponentTool;
use DevAction\Ui\Mcp\Tools\ScaffoldTemplateTool;
use DevAction\Ui\Mcp\UiServer;
use DevAction\Ui\Support\ComponentManifest;
use DevAction\Ui\Support\SkillMirror;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::preventStrayRequests());

afterEach(function () {
    File::deleteDirectory(scaffoldBase());
    ComponentManifest::flush();
});

/**
 * Fora do repositório e fora de vendor/ (o storage do Testbench fica dentro de vendor/).
 */
function scaffoldBase(): string
{
    return sys_get_temp_dir().'/devaction-ui-scaffold-'.getmypid();
}

/**
 * Raiz temporária mínima de checkout do kit; Scaffolder, manifesto e espelho apontam para ela.
 */
function scaffoldRoot(?string $base = null, bool $withTestCase = true): string
{
    $root = $base ?? scaffoldBase().'/kit';
    File::deleteDirectory($root);
    File::ensureDirectoryExists($root.'/resources/views/components');
    File::ensureDirectoryExists($root.'/skill/references');
    File::ensureDirectoryExists($root.'/tests');
    File::put($root.'/composer.json', '{"name":"devaction/ui"}');

    if ($withTestCase) {
        File::put($root.'/tests/TestCase.php', "<?php\n");
    }

    File::put($root.'/skill/SKILL.md', "# Skill\n\n## Components\n\n### Base\n\n- [Alpha](references/alpha.md) — A.\n- [Zeta](references/zeta.md) — Z.\n\n### Templates\n\n- [Auth](references/auth.md) — Auth.\n");
    File::put($root.'/skill/references/layouts.md', "---\nkind: layout\ngroup: Layouts\ncomponents: [layout.classic, layout.topbar, logo]\n---\n# Layouts\n\n## Classic\n\nx\n\n## Layout pieces\n\ny\n");
    File::put($root.'/resources/views/components/button.blade.php', "{{-- Button. --}}\n@props([])\n<button>{{ \$slot }}</button>\n");

    app()->bind(Scaffolder::class, fn () => new Scaffolder($root));
    app()->bind(ComponentManifest::class, fn () => new ComponentManifest($root));
    app()->bind(SkillMirror::class, fn () => new SkillMirror($root));
    ComponentManifest::flush();

    return $root;
}

/** @return list<string> */
function scaffoldTree(string $root): array
{
    $files = collect(File::allFiles($root))->map(fn ($file) => str_replace($root.'/', '', $file->getPathname()))->sort()->values()->all();

    return array_values($files);
}

it('S1 recusa traversal no nome e não cria nada', function () {
    $root = scaffoldRoot();
    $before = scaffoldTree($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => '../evil', 'group' => 'Base', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();

    expect(scaffoldTree($root))->toBe($before)
        ->and(file_exists(scaffoldBase().'/evil.blade.php'))->toBeFalse();
});

it('S2 recusa nomes fora da regex', function (string $name) {
    $root = scaffoldRoot();
    $before = scaffoldTree($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => $name, 'group' => 'Base', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();
    UiServer::tool(ScaffoldTemplateTool::class, ['name' => $name, 'kind' => 'template', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();

    expect(scaffoldTree($root))->toBe($before);
})->with(['a/b', 'Foo', 'a..b', '', 'a.b.c', '-a', 'a-', 'a--b', 'a\\b', "a\0b"]);

it('S3 recusa componente existente citando o caminho e não cria nada', function () {
    $root = scaffoldRoot();
    $before = scaffoldTree($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'button', 'group' => 'Base', 'summary' => 'S.', 'dry_run' => false])
        ->assertHasErrors()
        ->assertSee('resources/views/components/button.blade.php');

    expect(scaffoldTree($root))->toBe($before);
});

it('S4 um alvo existente aborta tudo (nenhum dos outros é criado)', function () {
    $root = scaffoldRoot();
    File::put($root.'/skill/references/probe.md', 'keep me');
    $before = scaffoldTree($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'summary' => 'S.', 'group' => 'Base', 'dry_run' => false])
        ->assertHasErrors()
        ->assertSee('skill/references/probe.md');

    expect(scaffoldTree($root))->toBe($before)
        ->and(File::get($root.'/skill/references/probe.md'))->toBe('keep me');
});

it('S5 dry_run omitido só planeja e não escreve nada', function () {
    $root = scaffoldRoot();
    $before = scaffoldTree($root);
    $skill = File::get($root.'/skill/SKILL.md');

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'summary' => 'S.', 'group' => 'Base'])
        ->assertOk()
        ->assertSee(['"planned"', 'resources/views/components/probe.blade.php', 'tests/Feature/ProbeComponentTest.php', 'skill/references/probe.md', 'skill/examples/components/probe.blade.php', 'skill/SKILL.md']);

    expect(scaffoldTree($root))->toBe($before)
        ->and(File::get($root.'/skill/SKILL.md'))->toBe($skill);

    $plan = (new Scaffolder($root))->plan('component', 'probe', ['group' => 'Base', 'summary' => 'S.']);
    expect(array_column($plan, 'path'))->toBe([
        'resources/views/components/probe.blade.php',
        'tests/Feature/ProbeComponentTest.php',
        'skill/references/probe.md',
        'skill/examples/components/probe.blade.php',
        'skill/SKILL.md',
    ]);
});

it('S6 raiz sob vendor/ esconde as tools de autoria', function () {
    $root = scaffoldRoot(scaffoldBase().'/vendor/devaction/ui');

    expect($root)->toContain('/vendor/')
        ->and(app(ScaffoldComponentTool::class)->eligibleForRegistration())->toBeFalse()
        ->and(app(ScaffoldTemplateTool::class)->eligibleForRegistration())->toBeFalse();

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Base', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();
    expect(File::exists($root.'/resources/views/components/probe.blade.php'))->toBeFalse();
});

it('S7 raiz sem tests/TestCase.php esconde as tools de autoria', function () {
    scaffoldRoot(null, false);

    expect(app(ScaffoldComponentTool::class)->eligibleForRegistration())->toBeFalse()
        ->and(app(ScaffoldTemplateTool::class)->eligibleForRegistration())->toBeFalse();
});

it('no checkout do kit as duas tools de autoria estão elegíveis', function () {
    scaffoldRoot();

    expect(app(ScaffoldComponentTool::class)->eligibleForRegistration())->toBeTrue()
        ->and(app(ScaffoldTemplateTool::class)->eligibleForRegistration())->toBeTrue();
});

it('scaffold real gera blade renderizável, teste Pest válido e índice ordenado', function () {
    $root = scaffoldRoot();

    UiServer::tool(ScaffoldComponentTool::class, [
        'name' => 'probe',
        'group' => 'Base',
        'summary' => 'A probe.',
        'props' => [['name' => 'tone', 'type' => 'string', 'default' => 'base', 'description' => 'Color | tone.']],
        'dry_run' => false,
    ])->assertOk()->assertSee('"created"');

    foreach (['resources/views/components/probe.blade.php', 'tests/Feature/ProbeComponentTest.php', 'skill/references/probe.md', 'skill/examples/components/probe.blade.php'] as $file) {
        expect(is_file($root.'/'.$file))->toBeTrue($file);
    }

    expect(fn () => token_get_all(File::get($root.'/tests/Feature/ProbeComponentTest.php'), TOKEN_PARSE))->not->toThrow(ParseError::class);

    Blade::anonymousComponentPath($root.'/resources/views/components', 'scaffoldtest');
    expect(Blade::render('<x-scaffoldtest::probe>Hello</x-scaffoldtest::probe>'))->toContain('Hello');

    $index = File::get($root.'/skill/SKILL.md');
    expect($index)->toMatch('/references\/alpha\.md.*references\/probe\.md.*references\/zeta\.md/s')
        ->and(File::get($root.'/skill/references/probe.md'))->toContain("kind: component\ngroup: Base\ncomponents: [probe]")
        ->and(File::exists($root.'/resources/ui-manifest.json'))->toBeTrue()
        ->and(File::get($root.'/resources/ui-manifest.json'))->toContain('"probe"');

    // segunda execução: tudo já existe, não sobrescreve
    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Base', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();
});

it('scaffold-component com children cria index e filhos', function () {
    $root = scaffoldRoot();

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'deck', 'group' => 'Base', 'summary' => 'S.', 'children' => ['card'], 'dry_run' => false])->assertOk();

    expect(is_file($root.'/resources/views/components/deck/index.blade.php'))->toBeTrue()
        ->and(is_file($root.'/resources/views/components/deck/card.blade.php'))->toBeTrue()
        ->and(File::get($root.'/skill/references/deck.md'))->toContain('components: [deck, deck.card]');
});

it('scaffold-template cria template e layout', function () {
    $root = scaffoldRoot();

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'landing', 'kind' => 'template', 'summary' => 'Landing.', 'dry_run' => false])->assertOk();
    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'wide', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])->assertOk();

    expect(File::get($root.'/skill/references/layouts.md'))->toContain('components: [layout.classic, layout.topbar, layout.wide, logo]')
        ->toMatch('/## Wide.*## Layout pieces/s')
        ->and(is_file($root.'/resources/views/components/landing/index.blade.php'))->toBeTrue()
        ->and(is_file($root.'/skill/examples/templates/landing.blade.php'))->toBeTrue()
        ->and(File::get($root.'/skill/SKILL.md'))->toContain('references/landing.md')
        ->and(is_file($root.'/resources/views/components/layout/wide.blade.php'))->toBeTrue()
        ->and(is_file($root.'/skill/examples/layouts/wide.blade.php'))->toBeTrue();
});

it('recusa texto de summary que fecha o comentário Blade', function () {
    $root = scaffoldRoot();
    $before = scaffoldTree($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Base', 'summary' => 'x --}}{{ system("id") }}', 'dry_run' => false])->assertHasErrors();

    expect(scaffoldTree($root))->toBe($before);
});

it('Scaffolder::write recusa caminho fora da raiz mesmo com plano forjado', function () {
    $root = scaffoldRoot();
    $scaffolder = new Scaffolder($root);

    expect(fn () => $scaffolder->write([['path' => '../outside.txt', 'contents' => 'x', 'mode' => 'create']]))->toThrow(InvalidArgumentException::class)
        ->and(fn () => $scaffolder->write([['path' => '/etc/x', 'contents' => 'x', 'mode' => 'create']]))->toThrow(InvalidArgumentException::class);

    // symlink dentro da raiz apontando para fora
    $outside = scaffoldBase().'/outside';
    File::ensureDirectoryExists($outside);
    symlink($outside, $root.'/link');

    expect(fn () => $scaffolder->write([['path' => 'link/x.txt', 'contents' => 'x', 'mode' => 'create']]))->toThrow(InvalidArgumentException::class)
        ->and(file_exists($outside.'/x.txt'))->toBeFalse();

    File::deleteDirectory($outside);
});

it('layout scaffold é reivindicado exatamente uma vez e a resposta traz next_steps', function () {
    $root = scaffoldRoot();
    File::ensureDirectoryExists($root.'/resources/views/components/layout');
    File::put($root.'/resources/views/components/layout/classic.blade.php', '{{-- c --}}');

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'wide', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])
        ->assertOk()
        ->assertSee('next_steps')
        ->assertSee("add 'layout.wide' to the GenerateTemplateTool map");

    $claims = [];

    foreach (glob($root.'/skill/references/*.md') ?: [] as $file) {
        if (preg_match('/^components: \[(.*)\]$/m', (string) file_get_contents($file), $m) === 1) {
            foreach (array_map('trim', explode(',', $m[1])) as $claimed) {
                $claims[$claimed] = ($claims[$claimed] ?? 0) + 1;
            }
        }
    }

    expect($claims['layout.wide'])->toBe(1);

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'wide', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])->assertHasErrors();
});
