<?php

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
