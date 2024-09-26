<?php

namespace Tests\Feature\Controllers\PostController;

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('requires authentication', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('it returns the correct component', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});
