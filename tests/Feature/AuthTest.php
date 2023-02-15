<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        // action
        // request to register
        $response = $this->post('api/register', [
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
        ]);

        $responseData = $response->json();

        // assert
        // assert if the user was created successfully
        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertStringContainsString('User created successfully', $responseData['message']);
    }

    public function test_login()
    {
        // preparation
        // create default user
        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);

        // action
        // request to login
        $response = $this->post('api/login', [
            'email' => 'admin@mail.com',
            'password' => 'password',
        ]);

        $responseData = $response->json();

        // assertion
        // assert if the response is a jwt and the code is 200
        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertStringContainsString('bearer', $responseData['type']);
    }

}