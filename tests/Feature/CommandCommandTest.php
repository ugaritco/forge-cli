<?php

use Ugarit\Forge\Resources\Command;
use Ugarit\Forge\Resources\Server;
use Ugarit\Forge\Resources\Site;

it('can run commands on sites with a menu', function () {
    $this->client->shouldReceive('server')->with('personal', 1)->andReturn(
        new Server(['id' => 1]),
    );

    $this->client->shouldReceive('serverSites')->with('personal', 1)->once()->andReturn(fakePaginator([
        new Site(['id' => 1, 'name' => 'pestphp.com']),
        new Site(['id' => 2, 'name' => 'something.com']),
    ]));

    $this->client->shouldReceive('createCommand')->with('personal', 1, 1, ['command' => 'php scribe clear'])->once();

    $this->client->shouldReceive('commands')->with('personal', 1, 1)->once()->andReturn(fakePaginator([
        new Command(['id' => 4, 'command' => 'php scribe clear', 'status' => 'running']),
    ]));

    $this->client->shouldReceive('command')->with('personal', 1, 1, 4)->once()->andReturn(
        new Command(['id' => 4, 'command' => 'php scribe clear', 'status' => 'finished']),
    );

    $this->client->shouldReceive('commandOutput')->with('personal', 1, 1, 4)->once()->andReturn(
        'Compiled services and packages files removed!',
    );

    $this->scribe('command')
        ->expectsSearch(
            'Which site would you like to run the command on',
            answer: 1,
            search: 'pestphp',
            answers: [1 => 'pestphp.com'],
        )
        ->expectsQuestion('What command would you like to execute', 'php scribe clear')
        ->expectsOutput('  ▕ Compiled services and packages files removed!')
        ->expectsPromptsInfo('Command run successfully.');
});

it('can run commands with an option', function () {
    $this->client->shouldReceive('server')->with('personal', 1)->andReturn(
        new Server(['id' => 1]),
    );

    $this->client->shouldReceive('serverSites')->with('personal', 1)->once()->andReturn(fakePaginator([
        new Site(['id' => 1, 'name' => 'pestphp.com']),
        new Site(['id' => 2, 'name' => 'something.com']),
    ]));

    $this->client->shouldReceive('createCommand')->with('personal', 1, 2, ['command' => 'php scribe list'])->once();

    $this->client->shouldReceive('commands')->with('personal', 1, 2)->once()->andReturn(fakePaginator([
        new Command(['id' => 3, 'command' => 'php scribe list', 'status' => 'running']),
    ]));

    $this->client->shouldReceive('command')->with('personal', 1, 2, 3)->once()->andReturn(
        new Command(['id' => 3, 'command' => 'php scribe list', 'status' => 'finished']),
    );

    $this->client->shouldReceive('commandOutput')->with('personal', 1, 2, 3)->once()->andReturn(
        'Compiled services and packages files removed!',
    );

    $this->scribe('command', ['site' => 2, '--command' => 'php scribe list'])
        ->expectsOutput('  ▕ Compiled services and packages files removed!')
        ->expectsPromptsInfo('Command run successfully.');
});

it('handles command failures', function () {
    $this->client->shouldReceive('server')->with('personal', 1)->andReturn(
        new Server(['id' => 1]),
    );

    $this->client->shouldReceive('serverSites')->with('personal', 1)->once()->andReturn(fakePaginator([
        new Site(['id' => 1, 'name' => 'pestphp.com']),
        new Site(['id' => 2, 'name' => 'something.com']),
    ]));

    $this->client->shouldReceive('createCommand')->with('personal', 1, 2, ['command' => 'php scribe migrate'])->once();

    $this->client->shouldReceive('commands')->with('personal', 1, 2)->once()->andReturn(fakePaginator([
        new Command(['id' => 3, 'command' => 'php scribe migrate', 'status' => 'running']),
    ]));

    $this->client->shouldReceive('command')->with('personal', 1, 2, 3)->once()->andReturn(
        new Command(['id' => 3, 'command' => 'php scribe migrate', 'status' => 'failed']),
    );

    $this->client->shouldReceive('commandOutput')->with('personal', 1, 2, 3)->once()->andReturn(
        ' Heritage\Database\QueryException',
    );

    $this->scribe('command', ['site' => 'something.com', '--command' => 'php scribe migrate']);
})->throws('The command failed.');
