<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Mail\OwnerNotfyMailRegisCreate;
use App\Notifications\Mail\RegisterCreateAdminNotify;

class OwnerNotifyRegisCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $owner;
    public $password;
    /**
     * Create a new job instance.
     */
    public function __construct($owner, $password)
    {
        $this->owner = $owner;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->owner->notify(new OwnerNotfyMailRegisCreate( $this->owner, $this->password));
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
