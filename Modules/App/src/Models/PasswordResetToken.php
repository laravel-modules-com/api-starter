<?php

declare(strict_types=1);

namespace Modules\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'email',
        'token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
