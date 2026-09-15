<?php

use Ugarit\Forge\Resources\Organization;

it('displays the list of organizations', function () {
    $this->client->shouldReceive('organizations')->andReturn(fakePaginator([
        new Organization(['id' => '1', 'name' => 'Personal', 'slug' => 'personal']),
        new Organization(['id' => '2', 'name' => 'Acme Inc', 'slug' => 'acme']),
    ]));

    $this->scribe('organization:list')
        ->expectsPromptsTable(['Name', 'Slug'], [
            ['Personal', 'personal'],
            ['Acme Inc', 'acme'],
        ]);
});

it('can be run via the org:list alias', function () {
    $this->client->shouldReceive('organizations')->andReturn(fakePaginator([
        new Organization(['id' => '1', 'name' => 'Personal', 'slug' => 'personal']),
    ]));

    $this->scribe('org:list')
        ->expectsPromptsTable(['Name', 'Slug'], [
            ['Personal', 'personal'],
        ]);
});
