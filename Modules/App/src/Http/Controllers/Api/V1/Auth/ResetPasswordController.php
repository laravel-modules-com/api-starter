<?php

declare(strict_types=1);

namespace Modules\App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Modules\App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use Modules\App\Models\PasswordResetToken;
use Modules\App\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @group Authentication
 */
class ResetPasswordController
{
    use ApiResponses;

    /**
     * Reset Password
     *
     * Update a users password by sending new passwords and token.
     *
     * @bodyParam email string required email of the user. No-example
     * @bodyParam password string required the password. No-example
     * @bodyParam password_confirmation string required the password confirmation. No-example
     *
     * @unauthenticated
     */
    public function index(ResetPasswordRequest $request, string $token): JsonResponse
    {
        $validated = $request->validated();

        $passwordReset = PasswordResetToken::where([
            'token' => $token,
            'email' => $validated['email'],
        ])->first();

        if (! $passwordReset) {
            return $this->error('Something went wrong. Please try again.', 422);
        }

        User::where('email', $validated['email'])->update([
            'password' => bcrypt(type($validated['password'])->asString()),
        ]);

        $passwordReset->delete();

        return $this->ok('Password updated.');
    }
}
