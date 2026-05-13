<?php

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function loanItem(array $attributes = []): Item
{
    return Item::create(array_merge([
        'name' => 'Switch',
        'serial_number' => 'SN-' . uniqid(),
        'location' => 'Armoire 02',
    ], $attributes));
}

function loanUser(array $attributes = []): User
{
    return User::create(array_merge([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ], $attributes));
}

// Relations

it('appartient à un item', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
    ]);

    expect($loan->item->id)->toBe($item->id);
});

it('appartient à un utilisateur', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
    ]);

    expect($loan->user->id)->toBe($user->id);
});

// getAnomalyAttribute

it('na pas danomalie si le statut est reserved et la date nest pas passée', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
    ]);

    expect($loan->anomaly)->toBeNull();
});

it('est expiré si le statut est reserved et la date est passée', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
        'status' => Loan::STATUS_RESERVED,
    ]);

    expect($loan->anomaly)->toBe('Expiré');
});

it('est en retard si le statut est borrowed et la date est passée sans retour', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
        'status' => Loan::STATUS_BORROWED,
        'end_date' => null,
    ]);

    expect($loan->anomaly)->toBe('En retard');
});

it('na pas danomalie si le statut est borrowed et la date nest pas passée', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date_planned' => now()->addDays(3)->format('Y-m-d'),
        'status' => Loan::STATUS_BORROWED,
        'end_date' => null,
    ]);

    expect($loan->anomaly)->toBeNull();
});

it('na pas danomalie si lobjet a été rendu même si la date est passée', function () {
    $item = loanItem();
    $user = loanUser();

    $loan = Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
        'status' => 'returned',
        'end_date' => now()->subDays(2)->format('Y-m-d'),
    ]);

    expect($loan->anomaly)->toBeNull();
});