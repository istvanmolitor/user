<?php

namespace Molitor\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthResponse",
    title: "Auth Response",
    description: "Successful login response",
    properties: [
        new OA\Property(property: "user", ref: "#/components/schemas/AuthUser"),
        new OA\Property(property: "token", type: "string", example: "1|abcdef123456..."),
        new OA\Property(property: "token_type", type: "string", example: "Bearer")
    ]
)]
class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new AuthUserResource($this->resource['user']),
            'token' => $this->resource['token'],
            'token_type' => 'Bearer',
        ];
    }
}
