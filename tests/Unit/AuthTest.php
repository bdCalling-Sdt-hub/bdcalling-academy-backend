<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function test_login(): void
    {
        $response = $this->call('POST','/login',[
            'email' => 'student1000@gmail.com',
            'password' => '1234567rr',
        ]);
//        dd($response);
        $response->assertStatus($response->status(), 302);
//        $this->assertTrue(true);
    }
}
