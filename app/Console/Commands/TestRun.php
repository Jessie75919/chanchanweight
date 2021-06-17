<?php

namespace App\Console\Commands;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Console\Command;
use Mail;

class TestRun extends Command
{
    protected $signature = 'test:it';
    protected $description = 'Command description';

    public function handle()
    {
        $user = User::find(1);
        Mail::to($user)->send(new ResetPasswordMail($user, 'http://localhost'));
    }
}
