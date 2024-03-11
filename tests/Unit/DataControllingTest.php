<?php

use App\Models\User;

test('ecosystem controller test', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getEcosystem'));

    expect($response->getStatusCode())
        ->toBe(200)
        ->and($response->getOriginalContent())
        ->toHaveKey('data');
});

test('file controller test', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('getPostFiles', 1));

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
