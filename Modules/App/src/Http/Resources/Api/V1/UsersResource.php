<?php

declare(strict_types=1);

namespace Modules\App\Http\Resources\Api\V1;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, array<string, Carbon|string|null>|int|string>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->resource->id,
            'attributes' => [
                'name' => $this->resource->name,
                'email' => $this->resource->email,
                'last_logged_in_at' => $this->resource->last_logged_in_at->format('Y-m-d H:i:s'),
                'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),
            ],
            'links' => [
                'self' => 'none', //route('api.v1.users.show', $this->id),
            ],
        ];
    }
}
