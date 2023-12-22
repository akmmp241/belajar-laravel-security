<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    public function testPolicy()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        Auth::login($user);

        $todo = Todo::query()->first();

        $this->assertTrue(Gate::allows('view', $todo));
        $this->assertTrue(Gate::allows('update', $todo));
        $this->assertTrue(Gate::allows('delete', $todo));
        $this->assertTrue(Gate::allows('create', Todo::class));
    }

    public function testAuthorizable()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        Auth::login($user);
        $todo = Todo::query()->first();
        $this->assertTrue($user->can('view', $todo));
        $this->assertTrue($user->can('update', $todo));
        $this->assertTrue($user->can('delete', $todo));
        $this->assertTrue($user->can('create', Todo::class));
    }

    public function testGuest()
    {
        self::assertTrue(Gate::allows('create', User::class));
    }

    public function testUser()
    {
        $this->seed([UserSeeder::class]);

        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        Auth::login($user);

        self::assertFalse(Gate::allows('create', User::class));

    }

    public function testSuperAdmin()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);
        $todo = Todo::query()->first();

        $user = new User([
            "name" => "superadmin",
            "email" => "superadmin@gmail.com",
            "password" => "superadmin",
            "token" => "superadmin"
        ]);

        $todo = Todo::query()->first();
        $this->assertTrue($user->can('view', $todo));
        $this->assertTrue($user->can('update', $todo));
        $this->assertTrue($user->can('delete', $todo));
        $this->assertTrue($user->can('create', Todo::class));
    }


}
