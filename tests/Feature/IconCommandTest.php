<?php

use DevAction\Ui\Console\IconCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\PendingCommand;

beforeEach(function () {
    $this->iconPath = storage_path('framework/testing/icons');
    File::deleteDirectory($this->iconPath);
});

afterEach(function () {
    File::deleteDirectory($this->iconPath);
});

test('ui:icon downloads hugeicons and writes blade icon files', function () {
    Http::fake([
        '*/dist/esm/CalendarAdd01Icon.js' => Http::response(<<<'JS'
            const CalendarAdd01Icon = [
              ["path", { d: "M16 2V6M8 2V6", stroke: "currentColor", strokeLinecap: "round", strokeLinejoin: "round", strokeWidth: "1.5", key: "0" }],
              ["circle", { cx: "12", cy: "12", r: "2", fill: "currentColor", stroke: "currentColor", strokeWidth: "2", key: "1" }]
            ];
            export { CalendarAdd01Icon as default };
            JS),
        '*' => Http::response('Not found', 404),
    ]);

    (new PendingCommand($this, $this->app, 'ui:icon', ['icons' => ['CalendarAdd01Icon', 'nao-existe'], '--path' => $this->iconPath]))
        ->expectsOutputToContain('calendar-add-01')
        ->expectsOutputToContain('não encontrado')
        ->assertFailed();

    expect(File::get($this->iconPath.'/calendar-add-01.blade.php'))
        ->toBe('<path d="M16 2V6M8 2V6" />'."\n".'<circle cx="12" cy="12" r="2" fill="currentColor" stroke-width="2" />'."\n")
        ->and(File::exists($this->iconPath.'/nao-existe.blade.php'))->toBeFalse();

    Http::assertSent(fn ($request) => str_contains($request->url(), '@hugeicons/core-free-icons@'.IconCommand::HUGEICONS_VERSION.'/dist/esm/CalendarAdd01Icon.js'));
});

test('ui:icon keeps existing icons unless forced', function () {
    Http::fake(['*' => Http::response('const A = [["path", { d: "M1 1", key: "0" }]];')]);
    File::ensureDirectoryExists($this->iconPath);
    File::put($this->iconPath.'/add-01.blade.php', 'original');

    (new PendingCommand($this, $this->app, 'ui:icon', ['icons' => ['add-01'], '--path' => $this->iconPath]))->expectsOutputToContain('já existe')->assertSuccessful();
    expect(File::get($this->iconPath.'/add-01.blade.php'))->toBe('original');

    (new PendingCommand($this, $this->app, 'ui:icon', ['icons' => ['add-01'], '--path' => $this->iconPath, '--force' => true]))->assertSuccessful();
    expect(File::get($this->iconPath.'/add-01.blade.php'))->toBe('<path d="M1 1" />'."\n");
});

test('ui:icon asks for names when none are given', function () {
    Http::fake(['*' => Http::response('const A = [["path", { d: "M2 2", key: "0" }]];')]);

    (new PendingCommand($this, $this->app, 'ui:icon', ['--path' => $this->iconPath]))
        ->expectsQuestion('Quais ícones? (separe por espaço, ex.: calendar-add-01 user-star-01)', 'star user-star-01')
        ->assertSuccessful();

    expect(File::exists($this->iconPath.'/star.blade.php'))->toBeTrue()
        ->and(File::exists($this->iconPath.'/user-star-01.blade.php'))->toBeTrue();
});

test('icon names are normalized from hugeicons module names', function (string $input, string $expected) {
    expect(app(IconCommand::class)->normalizeName($input))->toBe($expected);
})->with([
    ['calendar-add-01', 'calendar-add-01'],
    ['CalendarAdd01Icon', 'calendar-add-01'],
    ['ArrowDown01', 'arrow-down-01'],
    ['GitPullRequest', 'git-pull-request'],
    [' sparkles ', 'sparkles'],
]);
