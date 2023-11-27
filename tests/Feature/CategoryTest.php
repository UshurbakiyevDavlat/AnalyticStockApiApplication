<?php

use App\Enums\FeautureTestIntEnum;
use App\Enums\StatusCodeEnum;
use App\Models\Category;

it('can get categories', function () {
    Cache::shouldReceive('remember')->andReturn([]);
    $response = $this->get('/api/v1/categories');

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
    $categoryId = Category::find(FeautureTestIntEnum::MOCK_CATEGORY_ID)->id;

    // Mock the Cache facade
    Cache::shouldReceive('remember')->andReturn([]);

    $response = $this->get('/api/v1/categories/' . $categoryId);

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
