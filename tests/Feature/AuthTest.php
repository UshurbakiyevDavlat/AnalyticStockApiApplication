<?php

use App\Contracts\AuthInterface;
use App\Enums\AuthStrEnum;
use App\Enums\StatusCodeEnum;
use App\Http\Controllers\SSOAuthController;
use App\Models\SocialiteUser;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

it('implements AuthInterface', function () {
    $authController = new SSOAuthController(new AuthService());

    expect($authController)->toBeInstanceOf(AuthInterface::class);
});

it('handles OAuth provider callback', function () {
    // Arrange
    $user = User::create([
        'name' => 'test',
        'email' => fake()->safeEmail(),
        'azure_token' => fake()->word(),
    ]);

    $userEmail = $user->email;
    $userToken = fake()->word();
    $userName = $user->name;

    // Mock the Socialite facade to return a fake driver
    Socialite::shouldReceive('driver->user')->andReturn(
        new SocialiteUser($userEmail, $userToken, $userName),
    );

    // Act - Make a request to the OAuth callback route
    $response = $this->withoutMiddleware()->get('/auth/callback');

    // Assert
    $response->assertStatus(StatusCodeEnum::FOUND->value); // Check for a 302 (temporary redirect) status

    // Retrieve the user from the database
    $existingUser = User::whereBlind('email', 'email_index', $userEmail)->first();

    // Check that the user's Azure token was updated
    expect($existingUser->azure_token)->toBe($userToken);

    // Check that the user is authenticated
    $this->assertAuthenticatedAs($existingUser);
});

it('returns an error for a non-existing user', function () {
    // Arrange
    $nonExistingUserEmail = 'non-existing@example.com';
    $userToken = 'token-azure';
    $userName = 'John Doe';

    // Mock the Socialite facade to return a fake driver
    Socialite::shouldReceive('driver->user')->andReturn(
        new SocialiteUser($nonExistingUserEmail, $userToken, $userName),
    );

    // Act
    $response = $this->get('/auth/callback'); // Adjust the route as needed

    // Assert
    $response->assertStatus(StatusCodeEnum::FOUND->value);
});

it('returns JsonResponse from user method', function () {
    $authController = new SSOAuthController(new AuthService());

    $response = $authController->user();

    expect($response)->toBeInstanceOf(JsonResponse::class);
});

it('jwt auth controller returns JsonResponse from login method', function () {
    $email = User::where('id', 1)->first()->email;

    $response = $this->post(route('jwt.auth'), ['email' => $email]);

    expect($response->getStatusCode())->toBe(200);
});

it('get jwt profile', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('user.profile'));

    expect($response->getStatusCode())->toBe(200);
});

it('get sso user', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('sso.auth'));

    expect($response->getStatusCode())->toBe(200);
});

it('logout sso', function () {
    $user = User::find(1);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => $token,
    ])
        ->get(route('sso.logout'));

    expect($response->getStatusCode())->toBe(302);
});

it('login sso', function () {
    $response = $this->get(route('sso.login'));

    expect($response->getStatusCode())->toBe(302);
});

it('getting callback', function () {
    $user = Mockery::mock('Laravel\Socialite\Two\User');
    $user->shouldReceive('getId')->andReturn(1);
    $user->shouldReceive('getEmail')->andReturn('test@example.com');
    $user->shouldReceive('getName')->andReturn('John Doe');
    // Add any other methods you want to mock and their return values

    // Mock the Socialite driver for Azure and return the mocked user
    Socialite::shouldReceive('driver')
        ->with('azure')
        ->andReturn($provider = Mockery::mock('Laravel\Socialite\Contracts\Provider'));

    $provider->shouldReceive('user')
        ->andReturn($user);

    $response = $this->get(route('sso.callback'))
        ->withCookie(config('app.env') . '_' . AuthStrEnum::SOURCE_COOKIE->value);

    expect($response->getStatusCode())->toBe(302);
});
