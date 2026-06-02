<?php

namespace Molitor\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PermissionGroup',
    title: 'Permission Group',
    description: 'Permission group information',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'User management'),
        new OA\Property(property: 'permissions_count', type: 'integer', example: 12),
    ]
)]
class PermissionGroupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions_count' => $this->when(isset($this->permissions_count), (int) $this->permissions_count),
        ];
    }
}
