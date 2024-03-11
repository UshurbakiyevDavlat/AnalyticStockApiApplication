<?php

use App\Enums\StatusCodeEnum;
use App\Http\Requests\Post\BookmarkRequest;
use App\Http\Requests\Post\SearchRequest;
use App\Http\Resources\HorizonDatasetResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostUserDataResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;

test('/get searched posts', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $post = Post::find(1);
    $title = explode(' ', $post->title)[0];

    $request = new SearchRequest();
    $request->merge([
        'query' => $title,
    ]);

    $validated = $request->validate([
        'query' => 'required|string',
    ]);

    expect($validated)
        ->toBe([
            'query' => $title,
        ]);

    $postServiceMock = $this->mock(PostService::class);
    $postServiceMock->shouldReceive('searchPost')
        ->with($title)
        ->andReturn(Post::search($title)->get());

    $response = $this->withHeaders([
        'Authorization' => $token,
        'Lang' => 'en',
    ])
        ->get(route('searchPosts', ['query' => $title]));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value);

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
        'Lang' => 'en',
    ])
        ->get(route('getPostUserData'));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
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
        'Lang' => 'en',
    ])
        ->get(
            route('getPosts', $data),
        );

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data']['data'])
        ->toEqual(PostCollection::make($postData)->jsonSerialize()['data']);
});

test('/get post by id', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    $postServiceMock = $this->mock(PostService::class)->makePartial();
    $postServiceMock->shouldReceive('getPost')
        ->with(1)
        ->andReturnSelf();

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
        'Lang' => 'en',
    ])->get(route('getPost', 1));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual(PostResource::make(Post::find(1))->jsonSerialize());
});

test('/get post bookmarks of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get(route('getBookmarks'));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual($user->bookmarks->pluck('id')->toArray());
});

test('/get post horizon data of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get(route('getHorizonData', 1));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual(
            HorizonDatasetResource::make(Post::find(1)->horizonDataset()->first())->jsonSerialize(),
        );
});

test('/get post likes of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get(route('getLikes'));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual($user->likes->pluck('id')->toArray());
});

test('/get post views of authenticated user', function () {
    // Assuming you have a user with ID 1
    $user = User::find(1);

    // Generate a JWT token for the user
    $token = JWTAuth::fromUser($user);

    // Make a GET request to the /api/v1/posts/ endpoint with the generated token
    $response = $this->withHeaders([
        'Authorization' => $token,
    ])->get(route('getViews'));

    expect($response->getStatusCode())
        ->toBe(StatusCodeEnum::OK->value)
        ->and($response->getOriginalContent()['data'])
        ->toEqual(
            PostCollection::make(
                $user->views()->get(),
            )->jsonSerialize(),
        );
});

test('bookmark or unbookmark post', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $request = new BookmarkRequest();
    $request->merge([
        'favouriteable_id' => 1,
    ]);

    $validated = $request->validate([
        'favouriteable_id' => 'required|exists:posts,id',
    ]);

    expect($validated)
        ->toBe([
            'favouriteable_id' => 1,
        ]);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('bookmarkPost'), [
            'favouriteable_id' => 1,
        ]);

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.post.bookmarked'),
            'data' => [],
        ]);

    $responseToUnbookmark = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('bookmarkPost'), [
            'favouriteable_id' => 1,
        ]);

    expect($responseToUnbookmark->getStatusCode())
        ->toBe(200)
        ->and($responseToUnbookmark->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.post.unbookmarked'),
            'data' => [],
        ]);
});

test('like or unlike post', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $request = new BookmarkRequest();
    $request->merge([
        'likeable_id' => 1,
    ]);

    $validated = $request->validate([
        'likeable_id' => 'required|exists:posts,id',
    ]);

    expect($validated)
        ->toBe([
            'likeable_id' => 1,
        ]);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('likePost'), [
            'likeable_id' => 1,
        ]);

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('message');

    $responseToUnlike = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('likePost'), [
            'likeable_id' => 1,
        ]);

    expect($responseToUnlike->getStatusCode())
        ->toBe(200)
        ->and($responseToUnlike->getOriginalContent())
        ->toHaveKey('message');
});
