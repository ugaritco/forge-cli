<?php

use Ugarit\Forge\Exceptions\NotFoundException;
use Ugarit\Forge\Resources\Organization;

it('gets the current organization', function () {
    $this->client->shouldReceive('organization')->with('acme')->andReturn(
        new Organization(['name' => 'Acme Inc', 'slug' => 'acme']),
    );

    $this->config->set('organization', 'acme');

    $this->scribe('organization:current')
        ->expectsPromptsInfo('You are currently within the Acme Inc (acme) organization context.');
});

it('can be run via the org:current alias', function () {
    $this->client->shouldReceive('organization')->with('acme')->andReturn(
        new Organization(['name' => 'Acme Inc', 'slug' => 'acme']),
    );

    $this->config->set('organization', 'acme');

    $this->scribe('org:current')
        ->expectsPromptsInfo('You are currently within the Acme Inc (acme) organization context.');
});

it('fails when no organization has been selected', function () {
    $this->config->flush();

    $this->scribe('organization:current');
})->throws('You have not selected an organization. Use the \'organization:switch\' command.');

it('may fail if the current organization no longer exists', function () {
    $this->client->shouldReceive('organization')->with('acme')->andThrow(
        new NotFoundException('The resource you are looking for could not be found.'),
    );

    $this->config->set('organization', 'acme');

    $this->scribe('organization:current');
})->throws('The resource you are looking for could not be found.');
