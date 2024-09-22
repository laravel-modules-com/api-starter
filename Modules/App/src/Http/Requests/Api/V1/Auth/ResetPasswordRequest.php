<?php

declare(strict_types=1);

namespace Modules\App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * @return array<string, array<int,Password|string|null>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ];
    }
}
