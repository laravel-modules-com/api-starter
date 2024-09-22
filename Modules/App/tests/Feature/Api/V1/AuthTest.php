<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Modules\App\Mail\Auth\SendResetPasswordMail;

use function Pest\Laravel\json;
use function Pest\Laravel\post;

test('cannot login with invalid details', function () {
    post(route('api.v1.login'), [])->assertSessionHasErrors();
});

test('password cannot be less than 9 characters', function () {
    $user = User::factory()->create();

    post(route('api.v1.login'), [
        'email' => $user->email,
        'password' => 'no',
    ])->assertRedirect();
});

test('cannot login with wrong password', function () {
    $user = User::factory()->create();

    post(route('api.v1.login'), [
        'email' => $user->email,
        'password' => 'vf63sch7wyyx',
    ])->assertStatus(401);
});

test('can login with valid details', function () {
    $user = User::factory()->create();

    json('post', route('api.v1.login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertValid();
});

test('can logout', function () {
    $this->authenticate();

    json('post', route('api.v1.logout'))
        ->assertValid()
        ->assertJson(['message' => 'Logged Out']);
});

test('can start forgot password process', function () {
    json('post', route('api.v1.forgot-password'),
        ['email' => fake()->email, 'url' => url('/')]
    )->assertValid();
});

test('can start forgot password process without a valid email', function () {
    json('post', route('api.v1.forgot-password'),
        ['email' => 'blah']
    )->assertStatus(422);
});

test('can start forgot password process without an email', function () {
    json('post', route('api.v1.forgot-password'),
        ['email' => '']
    )->assertStatus(422);
});

test('can send reset email', function () {
    Mail::fake();
    $user = User::factory()->create();

    json('post', route('api.v1.forgot-password'),
        ['email' => $user->email, 'url' => url('/')]
    )->assertStatus(200);

    //send invite email
    Mail::assertSent(SendResetPasswordMail::class);
});

test('can change password from token', function () {
    Mail::fake();
    $user = User::factory()->create();

    $response = post(route('api.v1.forgot-password'),
        ['email' => $user->email, 'url' => url('/')]
    )->assertStatus(200);
    $token = $response->json('token');

    post(route('api.v1.reset-password', $token), [
        'email' => $user->email,
        'password' => 'Tgdths6744gtjf4@2',
        'password_confirmation' => 'Tgdths6744gtjf4@2',
    ])->assertStatus(200);

});

test('cannot change password from token with mismatching passwords', function () {
    Mail::fake();
    $user = User::factory()->create();

    $response = post(route('api.v1.forgot-password'),
        ['email' => $user->email, 'url' => url('/')]
    )->assertStatus(200);
    $token = $response->json('token');

    post(route('api.v1.reset-password', $token), [
        'email' => $user->email,
        'password' => 'gj66@4e6744gtjf4@2',
        'password_confirmation' => 'gj66@4e6744',
    ])->assertSessionHasErrors();
});

test('cannot change password from token with no email', function () {
    Mail::fake();
    $user = User::factory()->create();

    $response = post(route('api.v1.forgot-password'),
        ['email' => $user->email, 'url' => url('/')]
    )->assertStatus(200);
    $token = $response->json('token');

    post(route('api.v1.reset-password', $token), [
        'password' => 'gj66@4e6744gtjf4@2',
        'password_confirmation' => 'gj66@4e6744',
    ])->assertSessionHasErrors();
});
