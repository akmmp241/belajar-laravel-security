<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GateTest extends TestCase
{
    public function testGate()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        Auth::login($user);

        $contact = Contact::query()->where('email', 'joko@gmail.com')->firstOrFail();

        self::assertTrue(Gate::allows('get-contact', $contact));
        self::assertTrue(Gate::allows('update-contact', $contact));
        self::assertTrue(Gate::allows('delete-contact', $contact));
    }

    public function testGateDisallow()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::query()->create([
            "name" => "Kamal",
            "email" => "kamal@gmail.com",
            "password" => Hash::make(env('DB_PASSWORD')),
            "token" => "keren"
        ]);
        Auth::login($user);

        $contact = Contact::query()->where('email', 'joko@gmail.com')->firstOrFail();

        self::assertTrue(Gate::denies('get-contact', $contact));
        self::assertTrue(Gate::denies('update-contact', $contact));
        self::assertTrue(Gate::denies('delete-contact', $contact));
    }

    public function testGateMethod()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        Auth::login($user);

        $contact = Contact::query()->where('email', 'joko@gmail.com')->firstOrFail();

        self::assertTrue(Gate::allows('get-contact', $contact));
        self::assertTrue(Gate::allows('update-contact', $contact));
        self::assertTrue(Gate::allows('delete-contact', $contact));

        self::assertTrue(Gate::any(['get-contact', 'update-contact', 'delete-contact'], $contact));
        self::assertFalse(Gate::none(['get-contact', 'update-contact', 'delete-contact'], $contact));
    }

    public function testGateNotLogin()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        $gate = Gate::forUser($user);
        $contact = Contact::query()->where('email', 'joko@gmail.com')->firstOrFail();

        self::assertTrue($gate->allows('get-contact', $contact));
        self::assertTrue($gate->allows('update-contact', $contact));
        self::assertTrue($gate->allows('delete-contact', $contact));
    }

    public function testGateResponse()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::query()->where('email', 'akmal@gmail.com')->firstOrFail();
        Auth::login($user);

        $response = Gate::inspect("create-contact");
        Log::debug($response);
        self::assertFalse($response->allowed());
        self::assertEquals("You're not admin", $response->message());
    }


}
