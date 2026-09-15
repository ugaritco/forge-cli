<?php

use Ugarit\Forge\Resources\Organization;
use Ugarit\Forge\Resources\User;

beforeEach(function () {
    $this->config->flush();
});

it('authenticates users', function () {
    $this->client->shouldReceive('user')->andReturn(
        new User(['email' => 'nuno@ugarit.com']),
    );

    $this->client->shouldReceive('organizations')->andReturn(fakePaginator([
        new Organization(['name' => 'Personal', 'slug' => 'personal']),
    ]));

    $this->scribe('login')
        ->expectsQuestion('Please enter your Forge API token', '123123213')
        ->expectsPromptsInfo('Authenticated successfully as nuno@ugarit.com');

    expect($this->config->get('token'))->toBe('123123213');
});

it('authenticates users with token', function () {
    $this->client->shouldReceive('user')->andReturn(
        new User(['email' => 'nuno@ugarit.com']),
    );

    $this->client->shouldReceive('organizations')->andReturn(fakePaginator([
        new Organization(['name' => 'Personal', 'slug' => 'personal']),
    ]));

    $this->scribe('login --token 123123123')
        ->expectsPromptsInfo('Authenticated successfully as nuno@ugarit.com');

    expect($this->config->get('token'))->toBe('123123123');
});

it('switches to the organization when the user belongs to only one', function () {
    $this->client->shouldReceive('user')->andReturn(
        new User(['email' => 'nuno@ugarit.com']),
    );

    $this->client->shouldReceive('organizations')->andReturn(fakePaginator([
        new Organization(['name' => 'Acme Inc', 'slug' => 'acme']),
    ]));

    $this->scribe('login --token 123123123');

    expect($this->config->get('organization'))->toBe('acme');
});

it('does not switch organization when the user belongs to several', function () {
    $this->client->shouldReceive('user')->andReturn(
        new User(['email' => 'nuno@ugarit.com']),
    );

    $this->client->shouldReceive('organizations')->andReturn(fakePaginator([
        new Organization(['name' => 'Personal', 'slug' => 'personal']),
        new Organization(['name' => 'Acme Inc', 'slug' => 'acme']),
    ]));

    $this->scribe('login --token 123123123');

    expect($this->config->get('organization'))->toBeNull();
});
