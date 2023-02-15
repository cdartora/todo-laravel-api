<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task()
    {
        // prepare
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        // action
        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this
            ->withHeaders($headers)
            ->post('/api/tasks', [
                'title' => 'go out with Otto',
                'description' => 'Lorem ipsum',
            ]);

        $responseData = $response->json();

        // assertion
        $response->assertStatus(201);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertStringContainsString('Task created successfully.', $responseData['message']);
    }

    public function test_all_tasks()
    {
        // prepare
        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);

        Task::factory()->create([
            'title' => 'go out with Otto',
            'description' => 'Lorem ipsum',
            'completed' => false,
            'user_id' => $user->getKey(),
        ])->toArray();

        $token = JWTAuth::fromUser($user);

        // action
        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this
            ->withHeaders($headers)
            ->get('/api/tasks');

        // assert
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'tasks' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'completed',
                        'user_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }
}