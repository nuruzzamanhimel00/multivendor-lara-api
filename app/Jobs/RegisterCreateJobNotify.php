<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Mail\RegisterCreateAdminNotify;

class RegisterCreateJobNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $owner;
    /**
     * Create a new job instance.
     */
    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $admin = User::find(1);
            $admin->notify(new RegisterCreateAdminNotify($admin, $this->owner));
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
