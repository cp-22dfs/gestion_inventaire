<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function loginAdmin(): User
{
    return User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => 'admin@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}

function loginUser(): User
{
    return User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => 'diogo@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);
}

it('admin peut se connecter et est redirigé vers le dashboard admin', function () {
    loginAdmin();

    $response = $this->post(route('login.post'), [
        'email' => 'admin@ceff.ch',
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin/dashboard');
});

it('utilisateur peut se connecter et est redirigé vers le dashboard utilisateur', function () {
    loginUser();

    $response = $this->post(route('login.post'), [
        'email' => 'diogo@ceff.ch',
        'password' => 'password',
    ]);

    $response->assertRedirect('/user/dashboard');
});

it('refuse la connexion avec un mauvais mot de passe', function () {
    loginUser();

    $response = $this->post(route('login.post'), [
        'email' => 'diogo@ceff.ch',
        'password' => 'mauvais_mot_de_passe',
    ]);

    $response->assertSessionHasErrors('email');
});

it('refuse la connexion avec un email inexistant', function () {
    $response = $this->post(route('login.post'), [
        'email' => 'inexistant@ceff.ch',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
});

it('refuse la connexion sans email', function () {
    $response = $this->post(route('login.post'), [
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
});

it('refuse la connexion sans mot de passe', function () {
    $response = $this->post(route('login.post'), [
        'email' => 'diogo@ceff.ch',
    ]);

    $response->assertSessionHasErrors('password');
});

it('un invité est redirigé vers login sil essaie daccéder à une page protégée', function () {
    $response = $this->get(route('user.dashboard'));

    $response->assertRedirect(route('login'));
});

it('un utilisateur déjà connecté ne peut pas accéder à la page login', function () {
    $user = loginUser();

    $response = $this->actingAs($user)->get(route('login'));

    $response->assertRedirect('/');
});