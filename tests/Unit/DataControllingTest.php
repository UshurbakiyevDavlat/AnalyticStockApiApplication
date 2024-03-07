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
