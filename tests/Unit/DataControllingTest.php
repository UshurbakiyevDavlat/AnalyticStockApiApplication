<?php

use App\Http\Requests\Post\SubscriptionRequest;
use App\Http\Requests\Post\ViewRequest;
use App\Models\Ecosystem;
use App\Models\Post;
use App\Models\User;

test('ecosystems getting', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getEcosystems'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('ecosystem getting with img', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $ecosystem = Ecosystem::find(1);
    $ecosystem->img = 'test.jpg';
    $ecosystem->save();

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getEcosystem', 1));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('ecosystem getting without img', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getEcosystem', 2));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('file controller test with attachment', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $post = Post::find(1);
    $post->attachment = 'test.pdf';
    $post->save();

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getPostFiles', 1));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('file controller test without attachment', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getPostFiles', 2));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('getting common filter data', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getCommonFilterData'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent()['data'])
        ->toHaveKeys([
            'country',
            'sector',
            'author',
            'ticker',
            'isin',
            'tag',
        ]);
});

test('getting countries', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getCountries'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('getting sectors', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getSectors'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('getting authors', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getAuthors'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('getting tickers', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getTickers'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('getting isins', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getIsins'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('getting tags', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getTags'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('subscribe to category', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $request = new SubscriptionRequest();
    $request->merge([
        'category_id' => 1,
    ]);

    $responseSubscribe = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('subscribeToCategory'), $request->all());

    expect($responseSubscribe->getStatusCode())
        ->toBe(200)
        ->and($responseSubscribe->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.post.category.subscribed'),
            'data' => [],
        ]);

    $responseUnsubscribe = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('subscribeToCategory'), $request->all());

    expect($responseUnsubscribe->getStatusCode())
        ->toBe(200)
        ->and($responseUnsubscribe->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.post.category.unsubscribed'),
            'data' => [],
        ]);
});

test('view post', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $request = new ViewRequest();
    $request->merge([
        'post_id' => 1,
    ]);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('viewPost'), $request->all());

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.post.viewed'),
            'data' => [],
        ]);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->post(route('viewPost'), $request->all());

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toBe([
            'success' => true,
            'message' => __('response.post.viewed'),
            'data' => [],
        ]);

    $user->views()->detach(1);
});

test('list fast line', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('fastLine.list'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});
