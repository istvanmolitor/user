<?php

namespace Molitor\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthUser",
    title: "Auth User",
    description: "Authenticated user information",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "John Doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
        new OA\Property(property: "email_verified_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "email_verified", type: "boolean", example: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
        new OA\Property(
            property: "permissions",
            type: "array",
            items: new OA\Items(type: "string"),
            example: ["user.view", "user.create"]
        )
    ]
)]
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
