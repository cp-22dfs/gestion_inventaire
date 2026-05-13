<?php

use App\Mail\LateReturnAdminMail;
use App\Mail\LateReturnUserMail;
use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(Tests\TestCase::class, RefreshDatabase::class);

function mailItem(): Item
{
    return Item::create([
        'name' => 'Switch',
        'serial_number' => 'SN-' . uniqid(),
        'location' => 'Armoire 02',
    ]);
}

function mailUser(): User
{
    return User::create([
        'name' => 'Diogo',
        'surname' => 'Soares',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'utilisateur',
    ]);
}

function mailAdmin(): User
{
    return User::create([
        'name' => 'Admin',
        'surname' => 'Admin',
        'email' => uniqid() . '@ceff.ch',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}

function mailLoan(Item $item, User $user): Loan
{
    return Loan::create([
        'item_id' => $item->id,
        'user_id' => $user->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date_planned' => now()->subDays(2)->format('Y-m-d'),
        'status' => 'borrowed',
        'end_date' => null,
    ]);
}

// LateReturnUserMail

it('le mail utilisateur a le bon sujet', function () {
    $item = mailItem();
    $user = mailUser();
    $loan = mailLoan($item, $user);

    $mail = new LateReturnUserMail($loan);

    expect($mail->envelope()->subject)->toBe("Rappel : retour de {$item->name} en retard");
});

it('le mail utilisateur utilise le bon template', function () {
    $item = mailItem();
    $user = mailUser();
    $loan = mailLoan($item, $user);

    $mail = new LateReturnUserMail($loan);

    expect($mail->content()->view)->toBe('emails.late-return-user');
});

it('le mail utilisateur est envoyé à la bonne adresse', function () {
    Mail::fake();

    $item = mailItem();
    $user = mailUser();
    $loan = mailLoan($item, $user);

    Mail::to($user->email)->send(new LateReturnUserMail($loan));

    Mail::assertSent(LateReturnUserMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

// LateReturnAdminMail

it('le mail admin a le bon sujet', function () {
    $item = mailItem();
    $user = mailUser();
    $loan = mailLoan($item, $user);

    $mail = new LateReturnAdminMail($loan);

    expect($mail->envelope()->subject)->toBe("Rappel admin : {$item->name} en retard");
});

it('le mail admin utilise le bon template', function () {
    $item = mailItem();
    $user = mailUser();
    $loan = mailLoan($item, $user);

    $mail = new LateReturnAdminMail($loan);

    expect($mail->content()->view)->toBe('emails.late-return-admin');
});

it('le mail admin est envoyé à la bonne adresse', function () {
    Mail::fake();

    $item = mailItem();
    $user = mailUser();
    $admin = mailAdmin();
    $loan = mailLoan($item, $user);

    Mail::to($admin->email)->send(new LateReturnAdminMail($loan));

    Mail::assertSent(LateReturnAdminMail::class, function ($mail) use ($admin) {
        return $mail->hasTo($admin->email);
    });
});