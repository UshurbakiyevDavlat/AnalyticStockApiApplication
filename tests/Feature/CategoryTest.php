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
            'data' => [
                'categories',
            ],
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
        ->get('/api/v1/posts/categories/' . $categoryId);

    // Assertions
    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'category',
            ],
        ]);
});
