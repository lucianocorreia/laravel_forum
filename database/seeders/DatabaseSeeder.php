<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->has(Comment::factory(15)->recycle($users))
            ->recycle($users)->create();


        User::factory()
            ->has(Post::factory(54))
            ->has(Comment::factory(100)->recycle($posts))
            ->create([
                'name' => 'Luciano Correia',
                'email' => 'luciano@correia.com',
            ]);
    }
}
