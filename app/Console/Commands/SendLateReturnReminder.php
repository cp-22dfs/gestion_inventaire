<?php

namespace App\Console\Commands;

use App\Mail\LateReturnAdminMail;
use App\Mail\LateReturnUserMail;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLateReturnReminder extends Command
{
    protected $signature = 'loans:send-reminders';
    protected $description = 'Envoie un mail de rappel pour les objets en retard';

    public function handle()
    {
        $today = now()->startOfDay();

        $loansEnRetard = Loan::with(['item', 'user'])
            ->where('status', 'borrowed')
            ->whereNull('end_date')
            ->where('end_date_planned', '<', $today->copy()->subDay())
            ->get();

        foreach ($loansEnRetard as $loan) {
            Mail::to($loan->user->email)->send(new LateReturnUserMail($loan));

            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                Mail::to($admin->email)->send(new LateReturnAdminMail($loan));
            }
        }

        $this->info(count($loansEnRetard) . ' rappel(s) envoyé(s).');
    }
}