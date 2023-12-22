<?php

namespace Tests\Feature;

use App\Helpers\ResponseCode;
use App\Models\Todo;
use App\Models\User;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class TodoTest extends TestCase
{
    public function testController()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $this->post('/api/todo')
            ->assertStatus(403);

        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        $this->actingAs($user)
            ->post('/api/todo')
            ->assertStatus(ResponseCode::HTTP_CREATED);
    }

    public function testView()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        $todos = Todo::all();

        $this->view('todos', [
            "todos" => $todos,
        ])
            ->assertSeeText('No Edit')
            ->assertSeeText('No Delete');

        $this->actingAs($user)
            ->view('todos', [
                "todos" => $todos,
            ])
            ->assertSeeText('Edit')
            ->assertSeeText('Delete')
            ->assertDontSeeText('No Edit')
            ->assertDontSeeText('No Delete');
    }
}
