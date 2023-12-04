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
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'typeId',
                    'categoryId',
                    'subcategoriesId',
                    'createdAt',
                    'content',
                    'likes',
                    'views',
                    'ticker' => [
                        'fullName',
                        'shortName',
                    ],
                    'country',
                    'author' => [
                        'id',
                        'name',
                        'avatar',
                    ],
                ],
            ],
        ]);
});
