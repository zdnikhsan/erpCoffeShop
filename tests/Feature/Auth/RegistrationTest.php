<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('first registered user is assigned the owner role and subsequent users are not', function () {
    // 1. Register first user
    $response1 = $this->post('/register', [
        'name' => 'First User',
        'email' => 'first@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $firstUser = \App\Models\User::where('email', 'first@example.com')->first();
    $this->assertTrue($firstUser->hasRole('owner'));

    // Logout first user to register another
    $this->post('/logout');

    // 2. Register second user
    $response2 = $this->post('/register', [
        'name' => 'Second User',
        'email' => 'second@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $secondUser = \App\Models\User::where('email', 'second@example.com')->first();
    $this->assertFalse($secondUser->hasRole('owner'));
});
