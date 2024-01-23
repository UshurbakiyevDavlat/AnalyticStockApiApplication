<?php

use App\Enums\FeautureTestIntEnum;
use App\Enums\StatusCodeEnum;
use App\Models\Category;
use App\Models\User;

it('can get categories', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    Cache::shouldReceive('remember')->andReturn([]);
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get('/api/v1/posts/categories');

    // Assertions
    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [],
        ]);
});

it('can get category by ID', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $categoryId = Category::find(FeautureTestIntEnum::MOCK_CATEGORY_ID)->id;

    // Mock the Cache facade
    Cache::shouldReceive('remember')->andReturn([]);

    $response = $this
        ->withHeaders([
            'Authorization' => $token,
        ])
        ->get('/api/v1/posts/categories/'.$categoryId);

    // Assertions
    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [],
        ]);
});

it('/get post categories subscription of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders(['Authorization' => $token])
        ->get('/api/v1/posts/categories/subscriptions');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});
