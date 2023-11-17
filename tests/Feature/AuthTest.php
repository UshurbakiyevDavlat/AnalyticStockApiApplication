<?php

use App\Contracts\AuthInterface;
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
    $userEmail = User::find(1)->email;
    $userToken = fake()->word();
    $userName = 'John Doe';

    // Mock the Socialite facade to return a fake driver
    Socialite::shouldReceive('driver->user')->andReturn(
        new SocialiteUser($userEmail, $userToken, $userName),
    );

    // Act - Make a request to the OAuth callback route
    $response = $this->withoutMiddleware()->get('/auth/callback');

    // Assert
    $response->assertStatus(302); // Check for a 302 (temporary redirect) status

    // Check that the user was created or updated in the database
    $this->assertDatabaseHas('users', [
        'email' => $userEmail,
    ]);

    // Retrieve the user from the database
    $existingUser = User::where('email', $userEmail)->first();

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
    $response->assertStatus(StatusCodeEnum::NOT_FOUND->value);
    $response->assertExactJson(
        [
            'message' => 'User not found',
            'data' => [],
            'success' => false,
        ],
    );
});

it('returns JsonResponse from user method', function () {
    $authController = new SSOAuthController(new AuthService());

    $response = $authController->user();

    expect($response)->toBeInstanceOf(JsonResponse::class);
});

it('returns JsonResponse from logout method', function () {
    $authController = new SSOAuthController(new AuthService());

    $response = $authController->logout();

    expect($response)->toBeInstanceOf(JsonResponse::class);
});