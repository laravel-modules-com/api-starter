<?php

declare(strict_types=1);

namespace Modules\App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\App\Http\Requests\Api\V1\Auth\LoginRequest;
use Modules\App\Http\Resources\Api\V1\UsersResource;
use Modules\App\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\JsonResponse;

use function now;

/**
 * @group Authentication
 *
 * APIs for authenticating requests.
 *
 * Tokens are used to authenticate requests.
 */
class AuthController
{
    use ApiResponses;

    /**
     * Login
     *
     * Login should receive the following fields inside the body request
     *
     * @bodyParam email string required email of the user Example: dave@dcblog.dev
     * @bodyParam password string required the password of the user Example: password
     *
     * @unauthenticated
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Wrong email or password', 401);
        }

        /** @var User $user */
        $user = $request->user();

        $user->update([
            'last_logged_in_at' => now(),
        ]);

        return $this->ok('Logged In', [
            'token' => $user->createToken(
                'API Token for '.$user->email,
                ['*'],
                now()->addHours(8)
            )->plainTextToken,
            'user' => new UsersResource($user),
        ]);
    }

    /**
     * Logout
     *
     * calling logout will destroy the token and remove the user session.
     *
     * @authenticated
     *
     * @return JsonResponse response
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return $this->ok('Logged Out');
    }
}
