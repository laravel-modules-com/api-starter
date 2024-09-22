<?php

declare(strict_types=1);

namespace Modules\App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $url,
        public string $token) {}

    public function build(): SendResetPasswordMail
    {
        return $this->to($this->user->email)
            ->subject('Reset Password Notification')
            ->markdown('app::mail.auth.reset-password');
    }
}
