<?php

namespace Molitor\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * AuthUserResource - Used for authenticated user responses with permissions
 *
 * This resource includes the user's permissions flattened from all user groups.
 * Used for login responses and the /me endpoint.
 */
class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Flatten permissions from all user groups
        $permissions = [];
        if ($this->relationLoaded('userGroups')) {
            foreach ($this->userGroups as $group) {
                if ($group->relationLoaded('permissions')) {
                    foreach ($group->permissions as $permission) {
                        $permissions[] = $permission->name;
                    }
                }
            }
        }
        $permissions = array_unique($permissions);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'email_verified' => !is_null($this->email_verified_at),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'user_groups' => UserGroupResource::collection($this->whenLoaded('userGroups')),
            'permissions' => $permissions,
        ];
    }
}
