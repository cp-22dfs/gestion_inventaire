<?php

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function dashAdmin(): User
{
    return User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}

function dashUser(): User
{
    return User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);
}

// admin()

it('admin peut accéder au dashboard admin', function () {
    $admin = dashAdmin();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
});

it('utilisateur ne peut pas accéder au dashboard admin', function () {
    $user = dashUser();

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

it('le dashboard admin affiche les items', function () {
    $admin = dashAdmin();

    Item::create(['name' => 'Switch', 'serial_number' => 'SN-001', 'location' => 'Armoire 02']);
    Item::create(['name' => 'Routeur', 'serial_number' => 'SN-002', 'location' => 'Armoire 03']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('items');
    expect($response->viewData('items')->count())->toBe(2);
});

// user()

it('utilisateur peut accéder au dashboard utilisateur', function () {
    $user = dashUser();

    $response = $this->actingAs($user)->get(route('user.dashboard'));

    $response->assertStatus(200);
});

it('admin ne peut pas accéder au dashboard utilisateur', function () {
    $admin = dashAdmin();

    $response = $this->actingAs($admin)->get(route('user.dashboard'));

    $response->assertStatus(403);
});

it('le dashboard utilisateur affiche les items', function () {
    $user = dashUser();

    Item::create(['name' => 'Switch', 'serial_number' => 'SN-001', 'location' => 'Armoire 02']);
    Item::create(['name' => 'Routeur', 'serial_number' => 'SN-002', 'location' => 'Armoire 03']);

    $response = $this->actingAs($user)->get(route('user.dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('items');
    expect($response->viewData('items')->count())->toBe(2);
});

it('invité ne peut pas accéder au dashboard admin', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

it('invité ne peut pas accéder au dashboard utilisateur', function () {
    $response = $this->get(route('user.dashboard'));

    $response->assertRedirect(route('login'));
});