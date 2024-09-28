<?php

namespace Tests\Feature\Controllers\PostController;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = [
        'title' => 'My First Post',
        'topic_id' => Topic::factory()->create()->getKey(),
        'body' => str_repeat('a', 100),
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $data = $this->validData;

    actingAs($user)
        ->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id
    ]);
});

it('redirects to the route show page', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), [...$this->validData, ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body'],
]);
