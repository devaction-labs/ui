<?php

use DevAction\Ui\Mcp\Scaffolder;
use DevAction\Ui\Mcp\Tools\ScaffoldComponentTool;
use DevAction\Ui\Mcp\Tools\ScaffoldTemplateTool;
use DevAction\Ui\Mcp\UiServer;
use DevAction\Ui\Support\ComponentManifest;
use DevAction\Ui\Support\SkillMirror;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::preventStrayRequests());

afterEach(function () {
    File::deleteDirectory(edgeBase());
    ComponentManifest::flush();
});

function edgeBase(): string
{
    return sys_get_temp_dir().'/devaction-ui-scaffold-edge-'.getmypid();
}

function edgeRoot(string $skill = "# Skill\n\n### Base\n\n- [Alpha](references/alpha.md) — A.\n\n### Form\n\n### Templates\n\n", ?string $layouts = "---\ncomponents: [layout.classic, logo]\n---\n# Layouts\n"): string
{
    $root = edgeBase().'/kit';
    File::deleteDirectory($root);
    File::ensureDirectoryExists($root.'/resources/views/components');
    File::ensureDirectoryExists($root.'/skill/references');
    File::ensureDirectoryExists($root.'/tests');
    File::put($root.'/composer.json', '{"name":"devaction/ui"}');
    File::put($root.'/tests/TestCase.php', "<?php\n");

    if ($skill !== '') {
        File::put($root.'/skill/SKILL.md', $skill);
    }

    if ($layouts !== null) {
        File::put($root.'/skill/references/layouts.md', $layouts);
    }

    app()->bind(Scaffolder::class, fn () => new Scaffolder($root));
    app()->bind(ComponentManifest::class, fn () => new ComponentManifest($root));
    app()->bind(SkillMirror::class, fn () => new SkillMirror($root));
    ComponentManifest::flush();

    return $root;
}

/** @return list<string> */
function edgeTree(string $root): array
{
    return array_values(collect(File::allFiles($root))->map(fn ($f) => str_replace($root.'/', '', $f->getPathname()))->sort()->all());
}

/** @return array<string, string> */
function edgeSnapshot(string $root): array
{
    $out = [];

    foreach (edgeTree($root) as $path) {
        $out[$path] = File::get($root.'/'.$path);
    }

    return $out;
}

it('root() devolve a raiz normalizada sem barra final', function () {
    expect((new Scaffolder('/tmp/x/'))->root())->toBe('/tmp/x')
        ->and((new Scaffolder('C:\\kit\\'))->root())->toBe('C:/kit');
});

it('raiz com vendor/ não é checkout do kit', function () {
    expect((new Scaffolder(edgeBase().'/vendor/devaction/ui'))->isKitCheckout())->toBeFalse();
});

it('grupo inválido de componente é recusado e nada é gravado', function () {
    $root = edgeRoot();
    $before = edgeSnapshot($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'summary' => 'S.', 'group' => 'Nope', 'dry_run' => false])
        ->assertHasErrors();

    expect(fn () => (new Scaffolder($root))->plan('component', 'probe', ['group' => 'Nope', 'summary' => 'S.']))->toThrow(InvalidArgumentException::class, 'Invalid group "Nope".')
        ->and(edgeSnapshot($root))->toBe($before);
});

it('filho com nome inválido é recusado e nada é gravado', function (string $child) {
    $root = edgeRoot();
    $before = edgeSnapshot($root);

    expect(fn () => (new Scaffolder($root))->plan('component', 'deck', ['summary' => 'S.', 'children' => [$child]]))->toThrow(InvalidArgumentException::class, 'Invalid child name');

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'deck', 'group' => 'Base', 'summary' => 'S.', 'children' => [$child], 'dry_run' => false])->assertHasErrors();

    expect(edgeSnapshot($root))->toBe($before);
})->with(['Card', 'a.b', '../x', 'a/b', '-a', '']);

it('prop inválida é recusada', function (array $prop) {
    $root = edgeRoot();

    expect(fn () => (new Scaffolder($root))->plan('component', 'probe', ['summary' => 'S.', 'props' => [$prop]]))->toThrow(InvalidArgumentException::class, 'Invalid prop definition.');
})->with([
    'nome com maiúscula inicial' => [['name' => 'Tone']],
    'nome não string' => [['name' => 5]],
    'sem nome' => [[]],
    'tipo inválido' => [['name' => 'tone', 'type' => 'String1']],
    'tipo não string' => [['name' => 'tone', 'type' => ['x']]],
]);

it('prop com default não escalar vira null e o texto longo é cortado', function () {
    $root = edgeRoot();

    $plan = (new Scaffolder($root))->plan('component', 'probe', [
        'summary' => str_repeat('a', 500),
        'props' => [['name' => 'tone', 'type' => 'string|null', 'default' => ['x'], 'description' => 'd']],
    ]);

    $blade = collect($plan)->firstWhere('path', 'resources/views/components/probe.blade.php');

    expect($blade['contents'])->toContain("'tone' => null")
        ->and($blade['contents'])->not->toContain(str_repeat('a', 201));
});

it('summary vazio é recusado e <? também', function () {
    $root = edgeRoot();

    expect(fn () => (new Scaffolder($root))->plan('component', 'probe'))->toThrow(InvalidArgumentException::class, 'Summary is required.')
        ->and(fn () => (new Scaffolder($root))->plan('component', 'probe', ['summary' => "  \n "]))->toThrow(InvalidArgumentException::class, 'Summary is required.')
        ->and(fn () => (new Scaffolder($root))->plan('component', 'probe', ['summary' => '<?php echo 1;']))->toThrow(InvalidArgumentException::class);
});

it('tools recusam summary vazio/espaços, group/kind ausentes e group no template', function () {
    $root = edgeRoot();
    $before = edgeSnapshot($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Base', 'summary' => '   ', 'dry_run' => false])->assertHasErrors();
    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();
    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Base', 'dry_run' => false])->assertHasErrors();
    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'probe', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();
    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'probe', 'kind' => 'template', 'summary' => ' ', 'dry_run' => false])->assertHasErrors();
    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'probe', 'kind' => 'template', 'group' => 'Form', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();

    expect(edgeSnapshot($root))->toBe($before);
});

it('mensagens de erro trazem a regra do nome e o filho inválido', function () {
    $root = edgeRoot();

    expect(fn () => (new Scaffolder($root))->plan('component', 'Foo', ['summary' => 'S.']))->toThrow(InvalidArgumentException::class, 'use kebab-case, optional one dot')
        ->and(fn () => (new Scaffolder($root))->plan('component', 'deck', ['summary' => 'S.', 'children' => ['Bad.x']]))->toThrow(InvalidArgumentException::class, 'Invalid child name "Bad.x"');
});

it('summary é neutralizado para markdown (cabeçalho, crases, fence, pipe)', function () {
    $root = edgeRoot();
    $plan = (new Scaffolder($root))->plan('component', 'probe', ['group' => 'Base', 'summary' => "# Titulo ```x``` a|b\nlinha"]);
    $ref = collect($plan)->firstWhere('path', 'skill/references/probe.md')['contents'];
    $index = collect($plan)->firstWhere('path', 'skill/SKILL.md')['contents'];

    expect($ref)->toContain('\\# Titulo \\`\\`\\`x\\`\\`\\` a\\|b linha')
        ->and($ref)->not->toContain('```x')
        ->and($index)->toContain('— \\# Titulo \\`\\`\\`x\\`\\`\\` a\\|b linha');
});

it('isKitCheckout exige composer.json devaction/ui válido', function () {
    $root = edgeRoot();

    expect((new Scaffolder($root))->isKitCheckout())->toBeTrue();

    File::put($root.'/composer.json', '{"name":"outro/pacote"}');
    expect((new Scaffolder($root))->isKitCheckout())->toBeFalse();

    File::put($root.'/composer.json', '{"description":"\"name\": \"devaction/ui\""}');
    expect((new Scaffolder($root))->isKitCheckout())->toBeFalse();

    File::put($root.'/composer.json', 'nao json');
    expect((new Scaffolder($root))->isKitCheckout())->toBeFalse();

    File::delete($root.'/composer.json');
    expect((new Scaffolder($root))->isKitCheckout())->toBeFalse();
});

it('sync() não altera o binding do container e falha com RuntimeException', function () {
    $root = edgeRoot();
    File::put($root.'/resources/views/components/button.blade.php', "{{-- Button. --}}\n@props([])\n<button>{{ \$slot }}</button>\n");
    $before = app(ComponentManifest::class);
    $path = $before->path();

    (new Scaffolder($root))->sync();

    expect(app(ComponentManifest::class)->path())->toBe($path);

    // resources/ui-manifest.json como diretório: gravar o manifesto falha
    File::delete($root.'/resources/ui-manifest.json');
    File::ensureDirectoryExists($root.'/resources/ui-manifest.json');

    expect(fn () => (new Scaffolder($root))->sync())->toThrow(RuntimeException::class);
});

it('SKILL.md sem o cabeçalho do grupo aborta tudo e não cria nada', function () {
    $root = edgeRoot("# Skill\n\n### Base\n\n- [Alpha](references/alpha.md) — A.\n");
    $before = edgeSnapshot($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'summary' => 'S.', 'group' => 'Overlay', 'dry_run' => false])
        ->assertHasErrors()
        ->assertSee('Group heading');

    expect(edgeSnapshot($root))->toBe($before);
});

it('SKILL.md ausente aborta o scaffold de componente sem criar nada', function () {
    $root = edgeRoot('');
    $before = edgeTree($root);

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Base', 'summary' => 'S.', 'dry_run' => false])->assertHasErrors();

    expect(edgeTree($root))->toBe($before);
});

it('template sem o cabeçalho Templates no SKILL.md aborta e não cria nada', function () {
    $root = edgeRoot("# Skill\n\n### Base\n\n");
    $before = edgeSnapshot($root);

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'landing', 'summary' => 'S.', 'kind' => 'template', 'dry_run' => false])->assertHasErrors();

    expect(edgeSnapshot($root))->toBe($before);
});

it('grupo vazio recebe a primeira linha logo após o cabeçalho', function () {
    $root = edgeRoot();

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'probe', 'group' => 'Form', 'summary' => 'P.', 'dry_run' => false])->assertOk();

    expect(File::get($root.'/skill/SKILL.md'))->toContain("### Form\n\n- [Probe](references/probe.md) — P.");
});

it('entrada já presente no índice não é duplicada', function () {
    $root = edgeRoot();
    $content = File::get($root.'/skill/SKILL.md');

    $plan = (new Scaffolder($root))->plan('component', 'alpha', ['group' => 'Base', 'summary' => 'S.']);
    $index = collect($plan)->firstWhere('path', 'skill/SKILL.md');

    expect($index['contents'])->toBe($content);
});

it('linha nova vai depois da última quando é a maior alfabeticamente', function () {
    $root = edgeRoot();

    UiServer::tool(ScaffoldComponentTool::class, ['name' => 'zulu', 'group' => 'Base', 'summary' => 'Z.', 'dry_run' => false])->assertOk();

    expect(File::get($root.'/skill/SKILL.md'))->toMatch('/alpha\.md.*\n- \[Zulu\]/s');
});

it('layouts.md ausente aborta o layout sem criar nada', function () {
    $root = edgeRoot(layouts: null);
    $before = edgeTree($root);

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'wide', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])
        ->assertHasErrors()
        ->assertSee('layouts.md not found');

    expect(edgeTree($root))->toBe($before);
});

it('layouts.md como symlink é recusado', function () {
    $root = edgeRoot(layouts: null);
    File::put(edgeBase().'/real-layouts.md', "---\ncomponents: [layout.classic]\n---\n");
    symlink(edgeBase().'/real-layouts.md', $root.'/skill/references/layouts.md');
    $before = edgeTree($root);

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'wide', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])->assertHasErrors();

    expect(edgeTree($root))->toBe($before)
        ->and(File::get(edgeBase().'/real-layouts.md'))->not->toContain('layout.wide');
});

it('layouts.md sem lista components inline aborta e não cria nada', function () {
    $root = edgeRoot(layouts: "---\ncomponents:\n  - layout.classic\n---\n# Layouts\n");
    $before = edgeSnapshot($root);

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'wide', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])
        ->assertHasErrors()
        ->assertSee('inline components list');

    expect(edgeSnapshot($root))->toBe($before);
});

it('layout já reivindicado aborta antes de gravar', function () {
    $root = edgeRoot();
    $before = edgeSnapshot($root);

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'classic', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])->assertHasErrors();

    expect(edgeSnapshot($root))->toBe($before);
});

it('layout sem seção "Layout pieces" ganha a seção no fim e entra em ordem na lista', function () {
    $root = edgeRoot(layouts: "---\ncomponents: [logo, layout.zeta]\n---\n# Layouts\n");

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'alpha', 'kind' => 'layout', 'summary' => 'Alpha shell.', 'dry_run' => false])->assertOk();

    $doc = File::get($root.'/skill/references/layouts.md');

    expect($doc)->toContain('components: [logo, layout.alpha, layout.zeta]')
        ->and($doc)->toEndWith("## Alpha\n\nAlpha shell.\n\nTODO: example in `skill/examples/layouts/alpha.blade.php`.\n");
});

it('layout maior que os existentes entra depois do último layout.*', function () {
    $root = edgeRoot();

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'zulu', 'summary' => 'S.', 'kind' => 'layout', 'dry_run' => false])->assertOk();

    expect(File::get($root.'/skill/references/layouts.md'))->toContain('components: [layout.classic, layout.zulu, logo]');
});

it('layout com nome com ponto usa subdiretório e slug com hífen', function () {
    $root = edgeRoot();

    $plan = (new Scaffolder($root))->plan('layout', 'admin.wide', ['summary' => 'S.']);

    expect(array_column($plan, 'path'))->toContain('resources/views/components/layout/admin/wide.blade.php', 'skill/examples/layouts/admin-wide.blade.php', 'tests/Feature/AdminWideLayoutTest.php');
});

it('write recusa raiz inexistente', function () {
    expect(fn () => (new Scaffolder(edgeBase().'/nao-existe'))->write([['path' => 'a.txt', 'contents' => 'x', 'mode' => 'create']]))
        ->toThrow(InvalidArgumentException::class, 'Package root does not exist.');
});

it('write recusa caminhos inseguros sem gravar nada', function (string $path) {
    $root = edgeRoot();
    $before = edgeTree($root);

    expect(fn () => (new Scaffolder($root))->write([
        ['path' => 'skill/ok.txt', 'contents' => 'x', 'mode' => 'create'],
        ['path' => $path, 'contents' => 'x', 'mode' => 'create'],
    ]))->toThrow(InvalidArgumentException::class, 'Unsafe path');

    expect(edgeTree($root))->toBe($before);
})->with(['', '/abs.txt', 'a/../b.txt', "a\0b.txt", 'a\\b.txt']);

it('write: update de arquivo inexistente ou symlink é recusado e o plano não grava nada', function () {
    $root = edgeRoot();
    File::put(edgeBase().'/target.md', 'target');
    symlink(edgeBase().'/target.md', $root.'/skill/link.md');
    $scaffolder = new Scaffolder($root);
    $before = edgeTree($root);

    expect(fn () => $scaffolder->write([
        ['path' => 'skill/new.txt', 'contents' => 'x', 'mode' => 'create'],
        ['path' => 'skill/missing.md', 'contents' => 'x', 'mode' => 'update'],
    ]))->toThrow(InvalidArgumentException::class, 'File to update not found');

    expect(fn () => $scaffolder->write([
        ['path' => 'skill/new.txt', 'contents' => 'x', 'mode' => 'create'],
        ['path' => 'skill/link.md', 'contents' => 'x', 'mode' => 'update'],
    ]))->toThrow(InvalidArgumentException::class, 'File to update not found');

    expect(edgeTree($root))->toBe($before)
        ->and(File::get(edgeBase().'/target.md'))->toBe('target');
});

it('write: create sobre symlink (inclusive pendente) é recusado e nada é gravado', function () {
    $root = edgeRoot();
    symlink(edgeBase().'/ghost.md', $root.'/skill/dangling.md');
    $scaffolder = new Scaffolder($root);

    expect(fn () => $scaffolder->write([
        ['path' => 'skill/first.txt', 'contents' => 'x', 'mode' => 'create'],
        ['path' => 'skill/dangling.md', 'contents' => 'x', 'mode' => 'create'],
    ]))->toThrow(InvalidArgumentException::class, 'File already exists');

    expect(is_file($root.'/skill/first.txt'))->toBeFalse()
        ->and(file_exists(edgeBase().'/ghost.md'))->toBeFalse();
});

it('write: diretório-pai que é symlink para fora, mesmo ainda inexistente abaixo, é recusado', function () {
    $root = edgeRoot();
    File::ensureDirectoryExists(edgeBase().'/outside');
    symlink(edgeBase().'/outside', $root.'/skill/escape');

    expect(fn () => (new Scaffolder($root))->write([['path' => 'skill/escape/deep/new/x.txt', 'contents' => 'x', 'mode' => 'create']]))
        ->toThrow(InvalidArgumentException::class, 'outside the package root');

    expect(File::isDirectory(edgeBase().'/outside/deep'))->toBeFalse();
});

it('write: cria diretórios ausentes, grava create e atualiza update', function () {
    $root = edgeRoot();

    $written = (new Scaffolder($root))->write([
        ['path' => 'a/b/c.txt', 'contents' => 'novo', 'mode' => 'create'],
        ['path' => 'skill/SKILL.md', 'contents' => 'atualizado', 'mode' => 'update'],
    ]);

    expect($written)->toBe(['a/b/c.txt', 'skill/SKILL.md'])
        ->and(File::get($root.'/a/b/c.txt'))->toBe('novo')
        ->and(File::get($root.'/skill/SKILL.md'))->toBe('atualizado');
});

it('scaffold de template com nome com ponto cria index em subdiretório', function () {
    $root = edgeRoot();

    UiServer::tool(ScaffoldTemplateTool::class, ['name' => 'auth.login', 'summary' => 'S.', 'kind' => 'template', 'dry_run' => false])->assertOk();

    expect(is_file($root.'/resources/views/components/auth/login/index.blade.php'))->toBeTrue()
        ->and(is_file($root.'/skill/references/auth-login.md'))->toBeTrue()
        ->and(File::get($root.'/skill/references/auth-login.md'))->toContain("kind: template\ngroup: Templates");
});

it('sync() aponta manifesto e espelho para a raiz e regenera o manifesto', function () {
    $root = edgeRoot();
    File::put($root.'/resources/views/components/button.blade.php', "{{-- Button. --}}\n@props([])\n<button>{{ \$slot }}</button>\n");

    (new Scaffolder($root))->sync();

    expect(File::exists($root.'/resources/ui-manifest.json'))->toBeTrue();
});

it('plan() recusa nome inválido mesmo chamado direto, sem passar pela tool', function (string $name) {
    expect(fn () => (new Scaffolder(edgeRoot()))->plan('component', $name, ['summary' => 'S.']))->toThrow(InvalidArgumentException::class, 'Invalid name');
})->with(['../evil', 'Foo', 'a/b', '']);
