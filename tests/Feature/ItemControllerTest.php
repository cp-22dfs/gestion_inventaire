<?php

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function itemAdmin(): User
{
    return User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}

function itemUser(): User
{
    return User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);
}

function itemCreate(array $attributes = []): Item
{
    return Item::create(array_merge([
        'name' => 'Switch',
        'serial_number' => 'SN-' . uniqid(),
        'location' => 'Armoire 02',
    ], $attributes));
}

function itemLoan(Item $item, User $user, array $attributes = []): Loan
{
    return Loan::create(array_merge([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
    ], $attributes));
}

// create

it('admin peut accéder au formulaire de création', function () {
    $admin = itemAdmin();

    $response = $this->actingAs($admin)->get(route('admin.items.create'));

    $response->assertStatus(200);
});

it('utilisateur ne peut pas accéder au formulaire de création', function () {
    $user = itemUser();

    $response = $this->actingAs($user)->get(route('admin.items.create'));

    $response->assertStatus(403);
});

// store

it('admin peut créer un item', function () {
    $admin = itemAdmin();

    $this->actingAs($admin)->post(route('admin.items.store'), [
        'name' => 'Switch',
        'serial_number' => 'SN-001',
        'location' => 'Armoire 02',
    ]);

    expect(Item::count())->toBe(1);
    expect(Item::first()->name)->toBe('Switch');
});

it('refuse la création dun item sans nom', function () {
    $admin = itemAdmin();

    $response = $this->actingAs($admin)->post(route('admin.items.store'), [
        'serial_number' => 'SN-001',
    ]);

    $response->assertSessionHasErrors('name');
    expect(Item::count())->toBe(0);
});

it('refuse la création dun item avec un serial number existant', function () {
    $admin = itemAdmin();
    itemCreate(['serial_number' => 'SN-DOUBLE']);

    $response = $this->actingAs($admin)->post(route('admin.items.store'), [
        'name' => 'Switch',
        'serial_number' => 'SN-DOUBLE',
    ]);

    $response->assertSessionHasErrors('serial_number');
    expect(Item::count())->toBe(1);
});

// edit

it('admin peut accéder au formulaire dedit', function () {
    $admin = itemAdmin();
    $item = itemCreate();

    $response = $this->actingAs($admin)->get(route('admin.items.edit', $item->id));

    $response->assertStatus(200);
});

// update

it('admin peut modifier un item', function () {
    $admin = itemAdmin();
    $item = itemCreate(['name' => 'Switch']);

    $this->actingAs($admin)->put(route('admin.items.update', $item->id), [
        'name' => 'Routeur',
        'serial_number' => $item->serial_number,
        'location' => 'Armoire 03',
    ]);

    expect(Item::first()->name)->toBe('Routeur');
    expect(Item::first()->location)->toBe('Armoire 03');
});

it('refuse la modification dun item sans nom', function () {
    $admin = itemAdmin();
    $item = itemCreate();

    $response = $this->actingAs($admin)->put(route('admin.items.update', $item->id), [
        'serial_number' => $item->serial_number,
    ]);

    $response->assertSessionHasErrors('name');
});

// destroy

it('admin peut supprimer un item', function () {
    $admin = itemAdmin();
    $item = itemCreate();

    $this->actingAs($admin)->delete(route('admin.items.destroy', $item->id));

    expect(Item::count())->toBe(0);
});

it('utilisateur ne peut pas supprimer un item', function () {
    $user = itemUser();
    $item = itemCreate();

    $this->actingAs($user)->delete(route('admin.items.destroy', $item->id));

    expect(Item::count())->toBe(1);
});

it('supprime le qr code associé quand on supprime un item', function () {
    Storage::fake('public');
    $admin = itemAdmin();
    $item = itemCreate(['qr_code' => 'qrcodes/qr-test.svg']);
    Storage::disk('public')->put('qrcodes/qr-test.svg', 'fake-content');

    $this->actingAs($admin)->delete(route('admin.items.destroy', $item->id));

    expect(Storage::disk('public')->exists('qrcodes/qr-test.svg'))->toBeFalse();
    expect(Item::count())->toBe(0);
});

// adminShow

it('admin peut voir la page de détail dun item', function () {
    $admin = itemAdmin();
    $item = itemCreate();

    $response = $this->actingAs($admin)->get(route('admin.items.show', $item->id));

    $response->assertStatus(200);
});

// userShow

it('utilisateur peut voir la page de détail dun item', function () {
    $user = itemUser();
    $item = itemCreate();

    $response = $this->actingAs($user)->get(route('items.show', $item->id));

    $response->assertStatus(200);
});

// borrowShow

it('utilisateur peut accéder à la page demprunt', function () {
    $user = itemUser();
    $item = itemCreate();

    $response = $this->actingAs($user)->get(route('borrow.show', $item->id));

    $response->assertStatus(200);
});

it('la page demprunt affiche les données de la réservation active', function () {
    $user = itemUser();
    $item = itemCreate();
    itemLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    $response = $this->actingAs($user)->get(route('borrow.show', $item->id));

    $response->assertStatus(200);
    $response->assertViewHas('currentLoan');
});

// generateQrCode

it('admin peut générer un qr code', function () {
    Storage::fake('public');
    $admin = itemAdmin();
    $item = itemCreate();

    $this->actingAs($admin)->post(route('admin.items.qr', $item->id));

    expect(Item::first()->qr_code)->not->toBeNull();
    expect(Storage::disk('public')->exists('qrcodes/qr-' . $item->id . '.svg'))->toBeTrue();
});

// choice

it('utilisateur peut accéder à la page de choix', function () {
    $user = itemUser();
    $item = itemCreate();

    $response = $this->actingAs($user)->get(route('choice.show', $item->id));

    $response->assertStatus(200);
});