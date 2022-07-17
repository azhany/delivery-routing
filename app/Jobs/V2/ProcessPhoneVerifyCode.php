<?php

namespace App\Jobs\V2;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\V2\SendOneTimePasswordAction;

class ProcessPhoneVerifyCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneVerify;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneVerify)
    {
        $this->phoneVerify = $phoneVerify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = "Snapx Verification Code : {$this->phoneVerify->code}";

        (new SendOneTimePasswordAction)->execute("{$this->phoneVerify->phone_code}{$this->phoneVerify->phone_number}", $message);
    }
}
