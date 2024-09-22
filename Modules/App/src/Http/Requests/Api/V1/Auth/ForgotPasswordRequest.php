<?php

declare(strict_types=1);

namespace Modules\App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'url' => 'required|string',
        ];
    }
}
