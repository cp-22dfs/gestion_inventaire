<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function logoutUser(): User
{
    return User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => 'diogo@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);
}

it('un utilisateur connecté peut se déconnecter', function () {
    $user = logoutUser();

    $this->actingAs($user)->post(route('logout'));

    expect(Auth::check())->toBeFalse();
});

it('après déconnexion lutilisateur est redirigé vers accueil', function () {
    $user = logoutUser();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect('/');
});

it('un invité ne peut pas accéder à la route logout', function () {
    $response = $this->post(route('logout'));

    $response->assertRedirect(route('login'));
});