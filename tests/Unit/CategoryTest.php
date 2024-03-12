<?php

use App\Enums\FeautureTestIntEnum;
use App\Enums\StatusActivityEnum;
use App\Enums\StatusCodeEnum;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;

it('can get categories ru', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    Cache::shouldReceive('remember')->andReturn([]);
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getCategories'));

    // Assertions
    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual(
            CategoryCollection::make(
                Category::whereNull('parent_id')
                    ->where('status_id', StatusActivityEnum::ACTIVE->value)
                    ->get(),
            )->jsonSerialize(),
        );
});

it('can get categories en', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    Cache::shouldReceive('remember')->andReturn([]);
    $response = $this->withHeaders([
        'Authorization' => $token,
        'Lang' => 'en',
    ])
        ->get(route('getCategories'));

    // Assertions
    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual(
            CategoryCollection::make(
                Category::whereNull('parent_id')
                    ->where('status_id', StatusActivityEnum::ACTIVE->value)
                    ->get(),
            )->jsonSerialize(),
        );
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
        ->get(route('getCategory', $categoryId));

    // Assertions
    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual(
            CategoryResource::make(Category::find($categoryId))->jsonSerialize(),
        );
});

it('/get post categories subscription of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders(['Authorization' => $token])
        ->get(route('getSubscriptions'));

    expect($response)
        ->assertStatus(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual($user->subscriptions->pluck('id')->toArray());
});
