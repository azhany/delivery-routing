<?php

namespace App\Jobs\V2;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\V2\SendOneTimePasswordAction;

class ProcessPasswordResetCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $passwordReset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = "Snapx Verification Code : {$this->passwordReset->code}";

        (new SendOneTimePasswordAction)->execute("{$this->passwordReset->phone_code}{$this->passwordReset->phone_number}", $message);
    }
}
