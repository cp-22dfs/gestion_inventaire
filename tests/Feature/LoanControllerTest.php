<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);


function makeItem(array $attributes = []): Item
{
    return Item::create(array_merge([
        'name' => 'Switch',
        'serial_number' => 'SN-' . uniqid(),
        'location' => 'Armoire 02',
    ], $attributes));
}

function makeUser(array $attributes = []): User
{
    return User::create(array_merge([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ], $attributes));
}

function makeAdmin(): User
{
    return User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}

function makeLoan(Item $item, User $user, array $attributes = []): Loan
{
    return Loan::create(array_merge([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
    ], $attributes));
}

// store

it('peut créer une réservation si lobjet est libre', function () {
    $user = makeUser();
    $item = makeItem();

    $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    expect(Loan::count())->toBe(1);
});

it('refuse une réservation en conflit avec une existante', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(5)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->addDays(2)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(4)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    $response->assertSessionHasErrors('conflict');
    expect(Loan::count())->toBe(1);
});

it('refuse une réservation qui englobe une existante', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(2)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(4)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(5)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    $response->assertSessionHasErrors('conflict');
    expect(Loan::count())->toBe(1);
});

it('refuse une réservation qui déborde à droite', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(4)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->addDays(3)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(6)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    $response->assertSessionHasErrors('conflict');
    expect(Loan::count())->toBe(1);
});

it('refuse une réservation qui déborde à gauche', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(3)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(6)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(4)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    $response->assertSessionHasErrors('conflict');
    expect(Loan::count())->toBe(1);
});

it('accepte une réservation qui ne chevauche pas', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(7)->format('Y-m-d'),
        'location' => 'BD12',
    ]);

    $response->assertSessionHasNoErrors();
    expect(Loan::count())->toBe(2);
});

// store transformation réservation en emprunt

it('transforme une réservation active en emprunt lors du scan', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $this->actingAs($user)->post(route('loan.store'), [
        'item_id' => $item->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => 'borrowed',
        'location' => 'BD12',
    ]);

    expect(Loan::first()->status)->toBe(Loan::STATUS_BORROWED);
    expect(Loan::count())->toBe(1);
});

// scan

it('redirige vers la page de choix si lobjet est libre', function () {
    $user = makeUser();
    $item = makeItem();

    $response = $this->actingAs($user)->post(route('scan.post'), [
        'serial_number' => $item->serial_number,
    ]);

    $response->assertRedirect(route('choice.show', $item->id));
});

it('redirige vers la page de choix si une réservation active existe pour cet utilisateur', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user)->post(route('scan.post'), [
        'serial_number' => $item->serial_number,
    ]);

    $response->assertRedirect(route('choice.show', $item->id));
});

it('rend lobjet si cest le bon utilisateur qui rescanne', function () {
    $user = makeUser();
    $item = makeItem();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_BORROWED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $this->actingAs($user)->post(route('scan.post'), [
        'serial_number' => $item->serial_number,
    ]);

    expect(Loan::first()->status)->toBe('returned');
    expect(Loan::first()->end_date)->not->toBeNull();
});

it('redirige vers occupied si un autre utilisateur scanne un objet emprunté', function () {
    $user1 = makeUser(['email' => 'user1@ceff.ch']);
    $user2 = makeUser(['email' => 'user2@ceff.ch']);
    $item = makeItem();

    makeLoan($item, $user1, [
        'status' => Loan::STATUS_BORROWED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user2)->post(route('scan.post'), [
        'serial_number' => $item->serial_number,
    ]);

    $response->assertRedirect(route('user.dashboard'));
    $response->assertSessionHas('occupied');
});

it('redirige vers occupied si un autre utilisateur scanne un objet réservé', function () {
    $user1 = makeUser(['email' => 'user1@ceff.ch']);
    $user2 = makeUser(['email' => 'user2@ceff.ch']);
    $item = makeItem();

    makeLoan($item, $user1, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($user2)->post(route('scan.post'), [
        'serial_number' => $item->serial_number,
    ]);

    $response->assertRedirect(route('user.dashboard'));
    $response->assertSessionHas('occupied');
});

it('retourne une erreur si le numéro de série nexiste pas', function () {
    $user = makeUser();

    $response = $this->actingAs($user)->post(route('scan.post'), [
        'serial_number' => 'INEXISTANT-999',
    ]);

    $response->assertSessionHasErrors('serial_number');
});

// index

it('admin peut voir la liste des loans', function () {
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $item = makeItem();
    $user = makeUser();
    makeLoan($item, $user);

    $response = $this->actingAs($admin)->get(route('admin.loans.index'));

    $response->assertStatus(200);
});

// edit

it('admin peut accéder au formulaire dedit dun loan', function () {
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $item = makeItem();
    $user = makeUser();
    $loan = makeLoan($item, $user);

    $response = $this->actingAs($admin)->get(route('admin.loans.edit', $loan->id));

    $response->assertStatus(200);
});

// update

it('admin peut modifier le statut dun loan en returned', function () {
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $item = makeItem();
    $user = makeUser();
    $loan = makeLoan($item, $user, ['status' => Loan::STATUS_BORROWED]);

    $this->actingAs($admin)->put(route('admin.loans.update', $loan->id), [
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'end_date' => now()->format('Y-m-d'),
        'status' => 'returned',
        'location' => 'BD12',
    ]);

    expect(Loan::first()->status)->toBe('returned');
});

it('admin peut modifier les dates dun loan', function () {
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $item = makeItem();
    $user = makeUser();
    $loan = makeLoan($item, $user);

    $newEndDate = now()->addDays(5)->format('Y-m-d');

    $this->actingAs($admin)->put(route('admin.loans.update', $loan->id), [
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => $newEndDate,
        'status' => Loan::STATUS_RESERVED,
        'location' => 'BD12',
    ]);

    expect(Loan::first()->end_date_planned)->toContain($newEndDate);
});

it('admin ne peut pas modifier un loan en créant un conflit', function () {
    $admin = makeAdmin();
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(8)->format('Y-m-d'),
    ]);

    $loan2 = makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(10)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(13)->format('Y-m-d'),
    ]);

    $response = $this->actingAs($admin)->put(route('admin.loans.update', $loan2->id), [
        'start_date' => now()->addDays(6)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(9)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
        'location' => 'BD12',
    ]);

    $response->assertSessionHasErrors('conflict');
});

// destroy

it('admin peut supprimer un loan', function () {
    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $item = makeItem();
    $user = makeUser();
    $loan = makeLoan($item, $user);

    $this->actingAs($admin)->delete(route('admin.loans.destroy', $loan->id));

    expect(Loan::count())->toBe(0);
});

it('utilisateur ne peut pas supprimer un loan', function () {
    $user = makeUser();
    $item = makeItem();
    $loan = makeLoan($item, $user);

    $this->actingAs($user)->delete(route('admin.loans.destroy', $loan->id));

    expect(Loan::count())->toBe(1);
});