<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HashTest extends TestCase
{
    public function testHash()
    {
        $password = "rahasia";
        $hash = \Illuminate\Support\Facades\Hash::make($password);

        $password2 = "rahasia";
        $hash2 = \Illuminate\Support\Facades\Hash::make($password);

        self::assertNotEquals($hash, $hash2);

        $result = Hash::check($password, $hash);
        self::assertTrue($result);
    }

}
