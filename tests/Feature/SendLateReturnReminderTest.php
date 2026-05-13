<?php

use App\Mail\LateReturnAdminMail;
use App\Mail\LateReturnUserMail;
use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('envoie un mail à lemprunteur et à ladmin pour un loan en retard', function () {
    Mail::fake();

    $admin = User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => 'admin@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $user = User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => 'diogo@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);

    $item = Item::create([
        'name' => 'Switch',
        'serial_number' => 'SN-001',
        'location' => 'Armoire 02',
    ]);

    Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
        'status' => 'borrowed',
        'end_date' => null,
    ]);

    $this->artisan('loans:send-reminders')->assertExitCode(0);

    Mail::assertSent(LateReturnUserMail::class);
    Mail::assertSent(LateReturnAdminMail::class);
});

it('nenvoie pas de mail si aucun loan en retard', function () {
    Mail::fake();

    $this->artisan('loans:send-reminders')->assertExitCode(0);

    Mail::assertNothingSent();
});

it('nenvoie pas de mail si le loan est rendu', function () {
    Mail::fake();

    $user = User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => 'diogo@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);

    $item = Item::create([
        'name' => 'Switch',
        'serial_number' => 'SN-001',
        'location' => 'Armoire 02',
    ]);

    Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
        'status' => 'returned',
        'end_date' => now()->subDays(1)->format('Y-m-d'),
    ]);

    $this->artisan('loans:send-reminders')->assertExitCode(0);

    Mail::assertNothingSent();
});