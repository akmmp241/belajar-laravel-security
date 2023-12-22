<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EncryptionTest extends TestCase
{
    public function testEncryption()
    {
        $value = "10520";

        $encrypted = Crypt::encryptString($value);
        $decrypted = Crypt::decryptString($encrypted);
        Log::info($encrypted);

        self::assertEquals($value, $decrypted);
    }

}
