<?php

namespace App\Jobs\V2;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\V2\EmailVerifyMail;
use Mail;

class ProcessEmailVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->deleteEmailVerifyIfExist();

        $this->storeEmailVerify();

        Mail::to($this->user->email)->send(new EmailVerifyMail($this->generateLink()));
    }

    public function deleteEmailVerifyIfExist()
    {
        if ($this->user->emailVerify) {
            $this->user->emailVerify->delete();
        }
    }

    public function storeEmailVerify()
    {
        $this->user->emailVerify = $this->user->emailVerify()->create([
            'expired_at' => now()->addDays(2)
        ]);
    }

    public function generateLink()
    {
        return urldecode(route('email.verify', [
            'token' => $this->user->emailVerify->id . '.' . hash_hmac('sha256', $this->user->emailVerify->id, $this->user->email)
        ]));
    }
}
