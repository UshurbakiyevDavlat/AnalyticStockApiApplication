<?php

use App\Enums\StatusCodeEnum;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostUserDataResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;

test('/get searched posts', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $post = Post::find(1);
    $title = explode(' ', $post->title)[0];

    $postServiceMock = $this->mock(PostService::class);
    $postServiceMock->shouldReceive('searchPost')
        ->with($title)
        ->andReturn(Post::search($title)->get());

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('searchPosts', ['query' => $title]));

    expect($response->getStatusCode())
        ->toBe(200);

});

test('/get post user data', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $postData = PostUserDataResource::make($user)->jsonSerialize();

    $postServiceMock = $this->mock(PostService::class)->makePartial();
    $postServiceMock->shouldReceive('getPostsUserData')
        ->andReturn($postData);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getPostUserData'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.success'),
            'data' => $postData,
        ]);
});

test('/get posts', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);
    $data = [
        'sort' => 'popularity',
        'category' => '1%2C2%2C3%2C4%2C5',
        'subCategory' => '8',
        'sector' => '1%2C2%2C3%2C4%2C5',
        'author' => '1%2C2%2C3%2C4%2C5',
        'ticker' => '1%2C2%2C3%2C4%2C5',
        'country' => '1%2C2%2C3%2C4%2C5',
        'isin' => '1%2C2%2C3%2C4%2C5',
        'start_date' => '1704045600000',
        'end_date' => '1704564000000',
        'page' => '1',
        'id' => '1',
        'tags' => '1%2C2%2C3%2C4%2C5',
    ];

    $postData = app(PostService::class)->getPosts($data);

    $postServiceMock = $this->mock(PostService::class)->makePartial();
    $postServiceMock->shouldReceive('getPosts')
        ->with($data)
        ->andReturnSelf();

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(
            route('getPosts', $data),
        );

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent()['data']['data'])
        ->toEqual(PostCollection::make($postData)->jsonSerialize()['data']);
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
