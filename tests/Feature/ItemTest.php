<?php

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use it;

uses(Tests\TestCase::class, RefreshDatabase::class);

function makeItem(): Item
{
    return Item::create([
        'name' => 'Switch',
        'serial_number' => 'SN-' . uniqid(),
        'location' => 'Armoire 02',
    ]);
}

function makeUser(): User
{
    return User::create([
        'name' => 'Benoît',
        'surname' => 'Dupont',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
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

// loans()

it('peut avoir plusieurs loans', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user);
    makeLoan($item, $user, [
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(8)->format('Y-m-d'),
    ]);

    expect($item->loans)->toHaveCount(2);
});

// isCurrentlyOccupied()

it('est libre sans aucun loan', function () {
    $item = makeItem();

    expect($item->isCurrentlyOccupied())->toBeFalse();
});

it('est occupé si quelquun la emprunté', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, ['status' => Loan::STATUS_BORROWED]);

    expect($item->isCurrentlyOccupied())->toBeTrue();
});

it('est occupé si une réservation est en cours aujourdhui', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    expect($item->isCurrentlyOccupied())->toBeTrue();
});

it('est libre si la réservation est dans le futur', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(8)->format('Y-m-d'),
    ]);

    expect($item->isCurrentlyOccupied())->toBeFalse();
});

it('est libre si la réservation est passée', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
    ]);

    expect($item->isCurrentlyOccupied())->toBeFalse();
});

it('est libre si lobjet a été rendu', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => 'returned',
        'end_date' => now()->format('Y-m-d'),
    ]);

    expect($item->isCurrentlyOccupied())->toBeFalse();
});

// currentLoan()

it('na pas de loan actif sans réservation', function () {
    $item = makeItem();

    expect($item->currentLoan())->toBeNull();
});

it('retourne le bon loan quand lobjet est emprunté', function () {
    $item = makeItem();
    $user = makeUser();

    $loan = makeLoan($item, $user, ['status' => Loan::STATUS_BORROWED]);

    expect($item->currentLoan()->id)->toBe($loan->id);
});

it('retourne le bon loan si une réservation est en cours', function () {
    $item = makeItem();
    $user = makeUser();

    $loan = makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
    ]);

    expect($item->currentLoan()->id)->toBe($loan->id);
});

it('na pas de loan actif si la réservation est future', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => Loan::STATUS_RESERVED,
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->addDays(8)->format('Y-m-d'),
    ]);

    expect($item->currentLoan())->toBeNull();
});

it('na pas de loan actif si lobjet a été rendu', function () {
    $item = makeItem();
    $user = makeUser();

    makeLoan($item, $user, [
        'status' => 'returned',
        'end_date' => now()->format('Y-m-d'),
    ]);

    expect($item->currentLoan())->toBeNull();
});