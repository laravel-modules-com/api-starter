<?php

declare(strict_types=1);

namespace Modules\App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use Modules\App\Mail\Auth\SendResetPasswordMail;
use Modules\App\Models\PasswordResetToken;
use Modules\App\Traits\ApiResponses;

/**
 * @group Authentication
 */
class ForgotPasswordController
{
    use ApiResponses;

    /**
     * Forgot Password
     *
     * Generate and sent a reset password email.
     *
     * @bodyParam email string required email of the user. No-example
     * @bodyParam url string required the url where the reset button will link to. A token will be appended to the end of the url. No-example
     *
     * @unauthenticated
     */
    public function index(ForgotPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $token = Str::random(32);

        if (! $user = User::where('email', $validated['email'])->first()) {
            return response()->json(['message' => 'Password reset request sent']);
        }

        PasswordResetToken::firstOrCreate(
            ['email' => $validated['email']],
            ['token' => $token]
        );

        Mail::send(new SendResetPasswordMail($user, type($validated['url'])->asString(), $token));

        return response()->json(['message' => 'Password reset request sent', 'token' => $token]);
    }
}
