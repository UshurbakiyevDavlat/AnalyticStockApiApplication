<?php

use App\Enums\StatusCodeEnum;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

test('/get posts', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});

test('/get post by id', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/1');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});

test('/get post bookmarks of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/bookmarks');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});

test('/get post horizon data of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/horizonData/1');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});

test('/get post likes of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/likes');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});

test('/get post subscription of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/subscriptions');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});

test('/get post views of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get('/api/v1/posts/views');

    $response->assertStatus(StatusCodeEnum::OK->value)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully executed',
            'data' => [],
        ]);
});
